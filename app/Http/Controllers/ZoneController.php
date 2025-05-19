<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Log;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ZoneController extends Controller
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
        $zones = [];

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $zones = Zone::query()->orderby('nom')->get();
        }else{
            $allZones = Zone::query()->orderby('nom')->get();
            //Get only user related groups
            foreach($allZones as $zone){

                if ($authUser->isResponsableZone() && $zone->id == $authZone->id){
                    $sous_zones[] = $zone;
                }
            }
        }

        return view('zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('zones.create');
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
            'nom' => 'required|string',
           'continent' => 'required|string',
           'pays' => 'required|string',
            'ville' => 'required|string'
        ]);

        //dd($request);
        Zone::create($data);

        return redirect()->route('zones.index')
                ->with('success', 'Zone ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        return view('zones.show', compact('zone'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zone)
    {
        return view('zones.edit', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        //
        $data = $request->validate([
            'nom' => 'required|string',
            'continent' => 'required|string',
            'pays' => 'required|string',
            'ville' => 'required|string'
        ]);

        $zone->update($data);

        return redirect()->route('zones.index')
            ->with('success', 'Zone updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        if(!empty($id)){
            Zone::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Return all sous-zone of a zone by id.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function getSousZone(Request $request)
    {

        $str = "";
        $zone_id = $request->input('zone_id');
        $sousZones = SousZone::where('zone_id', $zone_id)->get();

        if(!$sousZones->isEmpty()){
            $str = "<option value='' disabled selected>Choisissez une sous-zone</option>";
            foreach($sousZones as $sousZone){
                $str .= "<option value=".$sousZone->id." data-has_country=".$sousZone->has_country.">".$sousZone->nom."</option>";
            }
        }else{
            $str = "<option value=''>Aucune sous-zone trouvée</option>";
        }

        return response()->json(['status'=>'success', 'data'=>$str], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    /**
     * List all members of the zone
     **/
    public function listMembers(Request $request)
    {
        $zone = Zone::query()->find($request->input('id'));
       
        return view('zones.list-members',compact('zone'));
    }

    public function getUsersData(Request $request)
    {
        
        FacadesLog::info($request->all());
        $users = User::query()
            ->where('id', '!=', 1)
            ->whereHas('activeGroupes.sousZone.zone', function ($query) use ($request) {
                $query->where('id', $request->input('zone_id'));
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
            ->addColumn('zone', function ($row) {
                // Get the first active groupe, its sousZone, then zone
                return $row->groupes->where('pivot.actif', \App\Constantes::ETAT_ACTIF)
                        ->first()?->sousZone?->zone?->nom;
            })
            ->addColumn('groupe', function ($row) {
                return $row->groupes->where('pivot.actif', \App\Constantes::ETAT_ACTIF)
                        ->first()?->nom_groupe;
            })
            ->addColumn('categorie_sociale', function ($row) {
                // Assuming categorie_sociale is a column on users table
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

}
