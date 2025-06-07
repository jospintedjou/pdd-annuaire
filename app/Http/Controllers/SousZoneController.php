<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Groupe;
use App\Models\Pays;
use App\Models\Zone;
use App\Models\SousZone;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SousZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = auth()->user();
        $authZone = $authUser->zone();
        $authSousZone = $authUser->sousZone();
        $authGroupe = $authUser->groupeActif();
        $sous_zones = [];

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $sous_zones = SousZone::query()->orderby('nom')->get();
        }else{
            $allSousZones = SousZone::query()->orderby('nom')->get();
            //Get only user related groups
            foreach($allSousZones as $sousZone){

                if ($authUser->isResponsableSousZone() && $sousZone->id == $authSousZone->id){
                    $sous_zones[] = $sousZone;
                }elseif ($authUser->isResponsableZone() && $sousZone->zone->id == $authZone->id){
                    $sous_zones[] = $sousZone;
                }
            }
        }

        return view('sous_zones.index', compact('sous_zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $zones = Zone::all();

        return view('sous_zones.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'nom' => 'required|string',
            'quartier' => 'required|string',
            'zone_id' => 'required|exists:zones,id',
            'has_country' => 'required|boolean',
        ]);

        SousZone::create($data);

        return redirect()->route('sous_zones.index')
            ->with('success','Sous Zone cree avec succes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SousZone  $sousZone
     * @return \Illuminate\Http\Response
     */
    public function show(SousZone $sousZone)
    {
        return view('zones.show', compact('sousZone'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SousZone  $sousZone
     * @return \Illuminate\Http\Response
     */
    public function edit(SousZone $sous_zone)
    {
        //
        $zones = Zone::all();

        return view('sous_zones.edit', compact('sous_zone'), compact('zones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SousZone  $sousZone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SousZone $sousZone)
    {
        
        $data = $request->validate([
            'nom' => 'required|string',
            'quartier' => 'required|string',
            'zone_id' => 'required|exists:zones,id',
            'has_country' => 'required|boolean',
        ]);
        
        $sousZone->update($data);

        return redirect()->route('sous_zones.index')
            ->with('success', 'Sous Zones updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SousZone  $sousZone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        if(!empty($id)){
            Groupe::where('sous_zone_id', $id)->delete();
            SousZone::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * List all members of the zone
     **/

     public function listMembers(Request $request)
    {
        $sousZone = SousZone::query()->find($request->input('id'));
        if(!$sousZone){
            abort(404);
        }

        return view('sous_zones.list-members',compact('sousZone'));
    }

    public function getUsersData(Request $request)
    {
        $sousZone = SousZone::find($request->input('sous_zone_id'));
        if(!$sousZone){
            abort(404);
        }
        $users = User::query()
            ->where('id', '!=', 1)
            ->whereHas('activeGroupes.sousZone', function ($query) use ($sousZone) {
                $query->where('id', $sousZone->id);
            })
            ->with(['groupes', 'niveauEngagement']);
          
        // Apply column-specific search (DataTables sends columns[x][search][value])
        $columns = $request->input('columns');

        // Column 0: nom
        if (!empty($columns[0]['search']['value'])) {
            $users->where(function ($query) use ($columns) {
                $search = $columns[0]['search']['value'];
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        // Column 1: zone (via groupes.sousZone.zone.nom)
        if (!empty($columns[1]['search']['value'])) {
            $search = $columns[1]['search']['value'];
            $users->whereHas('groupes.sousZone.zone', function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%");
            });
        }

        // Column 2: groupe (groupes.nom_groupe)
        if (!empty($columns[2]['search']['value'])) {
            $search = $columns[2]['search']['value'];
            $users->whereHas('groupes', function ($query) use ($search) {
                $query->where('nom_groupe', 'like', "%{$search}%");
            });
        }

        // Column 3: categorie_sociale (assuming it is a direct column or relation — adjust as needed)
        if (!empty($columns[3]['search']['value'])) {
            $search = $columns[3]['search']['value'];
            $users->where('categorie_sociale', 'like', "%{$search}%");
        }

        // Column 4: niveau_engagement (relation)
        if (!empty($columns[4]['search']['value'])) {
            $search = $columns[4]['search']['value'];
            $users->whereHas('niveauEngagement', function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%");
            });
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('nom', function ($row) {
                return $row->nom . ' ' . $row->prenom;
            })
            /*->addColumn('zone', function ($row) {
                // Get the first active groupe, its sousZone, then zone
                return $row->groupes->where('pivot.actif', \App\Constantes::ETAT_ACTIF)
                        ->first()?->sousZone?->zone?->nom;
            })*/
             ->addColumn('sous-zone', function ($row) {
                // Get the first active groupe, and its sousZone
                return $row->groupes->where('pivot.actif', \App\Constantes::ETAT_ACTIF)
                        ->first()?->sousZone?->nom;
            })
            ->addColumn('groupe', function ($row) {
                return $row->groupes->where('pivot.actif', \App\Constantes::ETAT_ACTIF)
                        ->first()?->nom_groupe;
            })
            ->addColumn('profession', function ($row) {
                return $row->profession ?? '-';
            })
             ->addColumn('specialite', function ($row) {
                return $row->specialite ?? '-';
            })
            ->addColumn('categorie_sociale', function ($row) {
                return $row->categorie_sociale ?? '-';
            })
            ->addColumn('niveau_engagement', function ($row) {
                return $row->niveauEngagement?->nom;
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('users.edit', ['user' => $row->id]);
                $statsUrl = route('statistiques_membre', ['user' => $row->id]);
                $deleteUrl = route('users.destroy', $row->id);

                $buttons = '
                    <form action="' . $deleteUrl . '" method="POST">
                        ' . csrf_field() . method_field('DELETE') . '
                        <a href="' . $statsUrl . '" class="btn btn-primary btn-round" title="statistiques">
                            <i class="material-icons">bar_chart</i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-success btn-round" title="modifier">
                            <i class="material-icons">edit</i>
                        </a>';

                if (auth()->user()->isAdmin()) {
                    $buttons .= '
                        <button type="button"
                            class="btn btn-danger btn-round text-white"
                            data-href="' . $deleteUrl . '"
                            data-id="' . $row->id . '"
                            data-toggle="modal"
                            data-target="#confirm-delete">
                            <i class="material-icons">close</i>
                        </button>';
                }

                $buttons .= '</form>';

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function exportAll()
    {
        $users = User::where('id', '!=', 1)->with(['groupes', 'niveauEngagement'])->orderby('nom', 'asc')->get();

        return Excel::download(new UsersExport($users), 'users.xlsx'); // Using Laravel Excel
    }


    /**
     * Return all 'pays'' of a sous-zone by id.
     *
     * @param  \App\Models\SousZone  $sousZone
     * @return \Illuminate\Http\Response
     */
    public function getPays(Request $request)
    {

        $str = "";
        $sous_zone_id = $request->input('sous_zone_id');
        $countries = Pays::where('sous_zone_id', $sous_zone_id)->get();

        if(!$countries->isEmpty()){
            $str .= "<option value='' disabled selected>Choisissez un pays</option>";
            foreach($countries as $country){
                $str .= "<option value=".$country->id.">".$country->nom."</option>";
            }
        }else{
            $str = "<option value=''>Aucun pays trouvé</option>";
        }

        return response()->json(['status'=>'success', 'data'=>$str], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }
   
}
