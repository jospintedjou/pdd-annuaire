<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Exports\ActivitiesExport;
use App\Models\AnneeSpirituelle;
use App\Models\NiveauEngagement;
use App\Models\Participation;
use App\Models\User;
use App\Models\Zone;
use App\Models\Groupe;
use App\Models\Activite;
use App\Models\SousZone;
use App\Models\Apostolat;
use Illuminate\Http\Request;
use App\Models\ApostolatConcerne;
use App\Models\CategorieActivite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listMembers(Request $request)
    {
        $zone = Zone::query()->find($request->input('id'));
       
        if(!$zone){
            abort(404);
        }

        return view('zones.list-members',compact('zone'));
    }
    public function index()
    {
        return view('activite.index');
    }

    public function getActivitiesData(Request $request)
    {
        $activites = [];
        $zone = auth()->user()->zone();
        $sousZone = auth()->user()->sousZone();
        $groupe = auth()->user()->groupeActif();

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if(auth()->user()->isAdmin()){
            $activites = Activite::query()->orderby('nom', 'asc')->with(['categorieActivite']);
        }else{
            // TODO add with to eager load datas and enhance performance 
            $activites = Activite::query()
                ->where(function ($query) use ($groupe, $sousZone, $zone) {
                    $query->where(function ($subQuery) use ($groupe) {
                        // ACTIVITE_GROUPE
                        $subQuery->where('type_activite', Constantes::ACTIVITE_GROUPE)
                                ->where('groupe_id', $groupe->id);
                    })
                    ->orWhere(function ($subQuery) use ($sousZone) {
                        // ACTIVITE_SOUS_ZONALE
                        $subQuery->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                                ->where('sous_zone_id', $sousZone->id);
                    })
                    ->orWhere(function ($subQuery) use ($zone) {
                        // ACTIVITE_ZONALE
                        $subQuery->where('type_activite', Constantes::ACTIVITE_ZONALE)
                                ->where('zone_id', $zone->id);
                    })
                    ->orWhere('type_activite', Constantes::ACTIVITE_REGIONALE);
                })
                ->orderBy('nom', 'asc')
                ->with(['categorieActivite']);
        }

        Log::info($request->all());

        // Apply column-specific search (DataTables sends columns[x][search][value])
        $columns = $request->input('columns');

        // Column 0: category of activity
        if (!empty($columns[0]['search']['value'])) {
            $search = $columns[0]['search']['value'];
            $activites->whereHas('categorieActivite', function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%");
            });
        }

        // Column 1: activity
        if (!empty($columns[1]['search']['value'])) {
            $search = $columns[1]['search']['value'];
            $activites->where('nom', 'like', "%{$search}%");
        }

        // Column 2: concerned 
        if (!empty($columns[2]['search']['value'])) {
            $search = $columns[2]['search']['value'];
            
        }

        return DataTables::of($activites)
            ->addIndexColumn()
            ->addColumn('categorie', function ($row) {
                return $row->categorieActivite->nom;
            })
            ->addColumn('nom', function ($row) {
                return $row->nom;
            })
            ->addColumn('concernes', function ($row) {
                // Get the category of activity name
                return $row->concerned();
            })
            ->addColumn('date_debut', function ($row) {
                return $row->date_debut;
            })
            ->addColumn('date_fin', function ($row) {
                return $row->date_fin;
            })
            ->addColumn('heure_debut', function ($row) {
                return $row->heure_debut;
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('presences.create', $row->id);

                $buttons = '
                        <a href="' . $editUrl . '" class="btn btn-success btn-round" title="modifier">
                            <i class="material-icons">edit</i>
                        </a>';

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function exportAll()
    {
        $activites = Activite::query()->orderby('nom', 'asc')->with(['categorieActivite'])
        ->get();

        return Excel::download(new ActivitiesExport($activites), 'activites.xlsx'); // Using Laravel Excel
    }


    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zone::get();
        $sous_zones = SousZone::get();
        $groupes = Groupe::get();
        $apostolats = Apostolat::get();
        $categories = CategorieActivite::get();
        $annee_spirituelles = AnneeSpirituelle::get();

        return view('activite.create', compact('zones', 'sous_zones', 'groupes',
                'categories', 'apostolats', 'annee_spirituelles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'categorie_activite_id' => 'required|exists:categorie_activites,id',
            'nom' => 'required',
            'zone_id' => 'required_if:type_activite,'.Constantes::ACTIVITE_ZONALE.'exists:zones,id',
            'sous_zone_id' => 'required_if:type_activite,'.Constantes::ACTIVITE_SOUS_ZONALE.'|exists:sous_zones,id',
            'groupe_id' => 'required_if:type_activite,'.Constantes::ACTIVITE_GROUPE.'|exists:groupes,id',
            'annee_spirituelle' => 'required|exists:annee_spirituelles,id',
            'type_activite' => 'required|in:'.Constantes::ACTIVITE_REGIONALE.','.Constantes::ACTIVITE_ZONALE.','.
                                Constantes::ACTIVITE_SOUS_ZONALE.','.Constantes::ACTIVITE_GROUPE,
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
            'heure_debut' => 'required|date_format:H:i',
            'lieu' => 'required|string',
            'apostolat' => 'required|array|min:1',
            'apostolat.*' => 'exists:apostolats,id'
        ]);

        //Let's clear unused inputs depending on the type of activity
        SWITCH($data['type_activite']){
            case Constantes::ACTIVITE_REGIONALE:
                $data['rone_id'] = null;
                $data['sous_zone_id'] = null;
                $data['groupe_id'] = null;
                break;
            case Constantes::ACTIVITE_ZONALE:
                $data['sous_zone_id'] = null;
                $data['groupe_id'] = null;
                break;
            case Constantes::ACTIVITE_SOUS_ZONALE:
                $data['rone_id'] = null;
                $data['groupe_id'] = null;
                break;
            case Constantes::ACTIVITE_GROUPE:
                $data['rone_id'] = null;
                $data['sous_zone_id'] = null;
                break;
            default:
                $data['rone_id'] = null;
                $data['sous_zone_id'] = null;
                $data['groupe_id'] = null;
                break;
        }

        DB::beginTransaction();
        $activite = Activite::create($data);

        foreach ($data['apostolat'] as $apostolat) {
            ApostolatConcerne::create([
                'categorie_activite_id' => $activite->categorie_activite_id,
                'activite_id' => $activite->id,
                'apostolat_id' => $apostolat
            ]);
        }

        DB::commit();

        return redirect()->route('activites.index')
            ->with('message', 'Activité créé avec succes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function show(Activite $activite)
    {
        return view('activite.show', compact('activite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function edit(Activite $activite)
    {
        $zones = Zone::get();
        $sous_zones = SousZone::get();
        $groupes = Groupe::get();
        $apostolats = Apostolat::get();
        $categories = CategorieActivite::get();
        $annee_spirituelles = AnneeSpirituelle::get();

        return view('activite.edit', compact('activite', 'zones', 'sous_zones', 'groupes',
            'categories', 'apostolats', 'annee_spirituelles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activite $activite)
    {
        $data = $request->validate([
            'categorie_activite_id' => 'required|exists:categorie_activites,id',
            'nom' => 'required',
            'zone_id' => 'required_if:type_activite,'.Constantes::ACTIVITE_ZONALE.'exists:zones,id',
            'sous_zone_id' => 'required_if:type_activite,'.Constantes::ACTIVITE_SOUS_ZONALE.'|exists:sous_zones, ',
            'groupe_id' => 'required_if:type_activite,'.Constantes::ACTIVITE_GROUPE.'|exists:groupes,id',
            'annee_spirituelle' => 'required|exists:annee_spirituelles,id',
            'type_activite' => 'required|in:'.Constantes::ACTIVITE_REGIONALE.','.Constantes::ACTIVITE_ZONALE.','.
                Constantes::ACTIVITE_SOUS_ZONALE.','.Constantes::ACTIVITE_GROUPE,
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
            'heure_debut' => 'required|date_format:H:i',
            'lieu' => 'required|string',
            'apostolat' => 'required|array|min:1',
            'apostolat.*' => 'exists:apostolats,id'
        ]);

        //Let's clear unused inputs depending on the type of activity
        SWITCH($data['type_activite']){
            case Constantes::ACTIVITE_REGIONALE:
                $data['rone_id'] = null;
                $data['sous_zone_id'] = null;
                $data['groupe_id'] = null;
                break;
            case Constantes::ACTIVITE_ZONALE:
                $data['sous_zone_id'] = null;
                $data['groupe_id'] = null;
                break;
            case Constantes::ACTIVITE_SOUS_ZONALE:
                $data['rone_id'] = null;
                $data['groupe_id'] = null;
                break;
            case Constantes::ACTIVITE_GROUPE:
                $data['rone_id'] = null;
                $data['sous_zone_id'] = null;
                break;
            default:
                $data['rone_id'] = null;
                $data['sous_zone_id'] = null;
                $data['groupe_id'] = null;
                break;
        }

        DB::beginTransaction();
        $activite->update($data);

        $activite->apostolats()->detach();

        foreach ($data['apostolat'] as $apostolat) {

            $activite->apostolats()->attach($apostolat, [
                'categorie_activite_id' => $activite->categorie_activite_id,
                'activite_id' => $activite->id
            ]);
        }

        DB::commit();

        return redirect()->route('activites.index')
            ->with('message', 'Activité créé avec succes');
    }

    /**
     * Show all activities so that user can go further and record presence
     *
     * @return \Illuminate\Http\Response
     */
    public function presence(Request $request)
    {
        $zone = auth()->user()->zone();
        $sousZone = auth()->user()->sousZone();
        $groupe = auth()->user()->groupeActif();

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if(auth()->user()->isAdmin()){
            $activites = Activite::get();
        }else{
            $allActivites = Activite::query()->get();
            //Get only zone related activities
            //if activity of zone see only for user zone
            //If activity of sous-zone then see only sous-zone related
            //if activity of group then see only grpup related
            $activites = [];
            // TODO Optimize all queries like this
            foreach($allActivites as $activite){

                if ($activite->type_activite == Constantes::ACTIVITE_GROUPE && $activite->groupe_id == $groupe->id){
                    $activites[] = $activite;
                }elseif ($activite->type_activite == Constantes::ACTIVITE_SOUS_ZONALE && $activite->sous_zone_id == $sousZone->id){
                    $activites[] = $activite;
                }elseif ($activite->type_activite == Constantes::ACTIVITE_ZONALE && $activite->zone_id == $zone->id){
                    $activites[] = $activite;
                }elseif($activite->type_activite == Constantes::ACTIVITE_REGIONALE ){
                    //Regional activity
                    $activites[] = $activite;
                }
            }

        }
        return view('presences.index', compact('activites'));
    }

    public function createPresence(Request $request)
    {
        $activite = '';
        if($request->activite){
            $activite = Activite::find($request->activite);
        }
        if(!$activite || !$request->activite){
            abort(404);
        }

        //Get only zone related activities
        //if activity of zone see only for user zone
        //If activity of sous-zone then see only sous-zone related
        //if activity of group then see only grpup related
        $users = [];
        $allUsers = User::query()->where('id', '!=', 1)->orderby('nom', 'asc')->get();
        //dd($activite);
        foreach($allUsers as $user){

            if ($activite->type_activite == Constantes::ACTIVITE_GROUPE  && $activite?->groupe_id == $user?->groupeActif()?->id){
                $users[] = $user;
            }elseif ($activite->type_activite == Constantes::ACTIVITE_SOUS_ZONALE  && $activite?->sous_zone_id == $user?->sousZone()?->id){
                $users[] = $user;
            }elseif ($activite->type_activite == Constantes::ACTIVITE_ZONALE && $activite?->zone_id == $user?->zone()?->id){
                $users[] = $user;
            }elseif($activite->type_activite == Constantes::ACTIVITE_REGIONALE){
                //Regional activity
                $users[] = $user;
            }
        }

        $niveau_engagements = NiveauEngagement::get();
        $apostolats = Apostolat::orderBy('nom')->get();
        $groupes = Groupe::orderBy('nom_groupe')->get();
        $zones = Zone::orderBy('nom')->get();
        $sous_zones = SousZone::orderBy('nom')->get();

        return view('presences.create', compact('activite', 'users', 'niveau_engagements', 'apostolats', 'groupes', 'sous_zones', 'zones'));
    }

    public function storePresence(Request $request)
    {
        $data = $request->validate([
            'activite_id' => 'required|exists:activites,id',
            'user_id' => 'required|exists:users,id',
            'heure_arrivee' => 'required',
            'presence' => 'required'
        ]);

        //Delete existing user's presence for this specific activity
        $participation = Participation::where(['activite_id' => $request->activite_id,
            'user_id' => $request->user_id])->forceDelete();

        //Add to database if user is present
        if($request->presence){
            Participation::create($data);
        }

        return response()->json(['status'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id)){
            DB::beginTransaction();
            ApostolatConcerne::where('activite_id', $id)->delete();

            Activite::find($id)->delete();
            DB::commit();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }
}
