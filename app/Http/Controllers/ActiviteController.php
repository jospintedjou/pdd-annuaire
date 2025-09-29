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
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activites = [];
        $zone = auth()->user()->zone();
        $sousZone = auth()->user()->sousZone();
        $groupe = auth()->user()->groupeActif();

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if(auth()->user()->isAdmin()){
            $activites = Activite::with(['categorieActivite', 'zone', 'sousZone', 'groupe'])
                ->orderBy('nom', 'asc')
                ->get();
        }else{
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
                ->with(['categorieActivite', 'zone', 'sousZone', 'groupe'])
                ->orderBy('nom', 'asc')
                ->get();
        }

        return view('activite.index', compact('activites'));
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
                $editUrl = route('presences.create', ['activite' => $row->id]);

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
        // Validate and find activity with eager loading
        $activiteId = $request->activite;
        if (!$activiteId) {
            abort(404, 'Activity ID is required');
        }

        // Eager load related data to avoid N+1 queries
        $activite = Activite::with([
            'categorieActivite',
            'zone',
            'sousZone', 
            'groupe'
        ])->find($activiteId);

        if (!$activite) {
            abort(404, 'Activity not found');
        }

        // Cache supporting data for better performance (cache for 1 hour)
        $niveau_engagements = cache()->remember('niveau_engagements', 3600, function () {
            return NiveauEngagement::orderBy('nom')->get();
        });

        $apostolats = cache()->remember('apostolats', 3600, function () {
            return Apostolat::orderBy('nom')->get();
        });

        $groupes = cache()->remember('groupes', 3600, function () {
            return Groupe::orderBy('nom_groupe')->get();
        });

        return view('presences.create', compact('activite', 'niveau_engagements', 'apostolats', 'groupes'));
    }

    /**
     * Get users data for presence DataTable with server-side processing
     */
    public function getPresenceUsersData(Request $request)
    {
        $activiteId = $request->input('activite_id');
        
        // Cache activity data to avoid repeated queries
        $activite = cache()->remember("activite_{$activiteId}", 300, function () use ($activiteId) {
            return Activite::select('id', 'type_activite', 'groupe_id', 'sous_zone_id', 'zone_id', 'nom')
                ->find($activiteId);
        });
        
        if (!$activite) {
            return response()->json(['error' => 'Activity not found'], 404);
        }

        // Build optimized base query using Laravel's relationship methods
        $users = User::select('id', 'nom', 'prenom', 'categorie_sociale', 'niveau_engagement_id')
            ->where('id', '!=', 1);

        // Optimize filtering based on activity type using whereHas for better readability
        switch ($activite->type_activite) {
            case Constantes::ACTIVITE_GROUPE:
                $users->whereHas('activeGroupes', function ($query) use ($activite) {
                    $query->where('groupes.id', $activite->groupe_id);
                });
                break;
            case Constantes::ACTIVITE_SOUS_ZONALE:
                $users->whereHas('activeGroupes', function ($query) use ($activite) {
                    $query->where('groupes.sous_zone_id', $activite->sous_zone_id);
                });
                break;
            case Constantes::ACTIVITE_ZONALE:
                $users->whereHas('activeGroupes.sousZone', function ($query) use ($activite) {
                    $query->where('sous_zones.zone_id', $activite->zone_id);
                });
                break;
            case Constantes::ACTIVITE_REGIONALE:
                // All users can participate in regional activities - no additional filtering
                break;
            default:
                // Default to no users if activity type is unknown
                $users->whereRaw('1 = 0');
        }

        // Add eager loading for related data with optimized selects
        $users->with([
            'niveauEngagement:id,nom',
            'activeGroupes' => function ($query) {
                $query->select('groupes.id', 'groupes.nom_groupe', 'groupes.sous_zone_id')
                    ->with([
                        'sousZone:id,nom,zone_id',
                        'sousZone.zone:id,nom'
                    ]);
            }
        ]);

        // Apply column-specific search using Laravel's relationship methods
        $columns = $request->input('columns');

        // Column 1: nom (name) - search in both nom and prenom
        if (!empty($columns[1]['search']['value'])) {
            $search = $columns[1]['search']['value'];
            $users->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        // Column 2: groupe - use whereHas with relationship
        if (!empty($columns[2]['search']['value'])) {
            $search = $columns[2]['search']['value'];
            $users->whereHas('activeGroupes', function ($query) use ($search) {
                $query->where('nom_groupe', 'like', "%{$search}%");
            });
        }

        // Column 3: niveau_engagement - use whereHas with relationship
        if (!empty($columns[3]['search']['value'])) {
            $search = $columns[3]['search']['value'];
            $users->whereHas('niveauEngagement', function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%");
            });
        }

        // Preload all participations for this activity to avoid N+1 queries
        $participations = cache()->remember("participations_activity_{$activite->id}", 60, function () use ($activite) {
            return Participation::where('activite_id', $activite->id)
                ->pluck('heure_arrivee', 'user_id')
                ->toArray();
        });

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('nom', function ($row) {
                return $row->nom . ' ' . $row->prenom;
            })
            ->addColumn('groupe', function ($row) {
                return $row->activeGroupes->first()?->nom_groupe ?? '-';
            })
            ->addColumn('niveau_engagement', function ($row) {
                return $row->niveauEngagement?->nom ?? '-';
            })
            ->addColumn('heure_arrivee', function ($row) use ($participations) {
                $heureArrivee = isset($participations[$row->id]) ? $participations[$row->id] : '';
                
                return '
                    <div class="form-check">
                        <div class="form-group">
                            <input type="time" class="form-control timepicker heure_arrivee"
                                   step="3600" min="00:00" max="23:59" pattern="[0-2][0-9]:[0-5][0-9]"
                                   data-user-id="' . $row->id . '"
                                   value="' . $heureArrivee . '"/>
                        </div>
                    </div>
                ';
            })
            ->addColumn('presence_checkbox', function ($row) use ($activite, $participations) {
                $isPresent = isset($participations[$row->id]);
                $checked = $isPresent ? 'checked' : '';
                
                return '
                    <div class="form-check">
                        <div class="form-group">
                            <label class="form-check-label">
                                <input class="form-check-input presence-checkbox" type="checkbox" value="1"
                                       data-user-id="' . $row->id . '" 
                                       data-activite-id="' . $activite->id . '" 
                                       ' . $checked . '>
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['heure_arrivee', 'presence_checkbox'])
            ->make(true);
    }

    public function storePresence(Request $request)
    {
        $data = $request->validate([
            'activite_id' => 'required|exists:activites,id',
            'user_id' => 'required|exists:users,id',
            'heure_arrivee' => 'required_if:presence,1',
            'presence' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Use updateOrCreate for better performance and atomicity
            if ($request->presence) {
                Participation::updateOrCreate(
                    [
                        'activite_id' => $data['activite_id'],
                        'user_id' => $data['user_id']
                    ],
                    [
                        'heure_arrivee' => $data['heure_arrivee']
                    ]
                );
            } else {
                // Remove participation if unchecked
                Participation::where([
                    'activite_id' => $data['activite_id'],
                    'user_id' => $data['user_id']
                ])->delete();
            }

            DB::commit();

            // Clear related cache
            cache()->forget("participations_activity_{$data['activite_id']}");

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update presence'
            ], 500);
        }
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
