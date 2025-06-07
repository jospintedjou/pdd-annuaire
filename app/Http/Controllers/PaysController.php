<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\SousZone;
use App\Models\Pays;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaysController extends Controller
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
        $authPays = $authUser->groupeActif()?->pays();
        $authGroupe = $authUser->groupeActif();
        $paysArr = [];

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $paysArr = Pays::query()->orderby('nom')->get();
        }else{
            $allPays = Pays::query()->with('sousZone')->orderby('nom')->get();
            //Get only user related countries
            foreach($allPays as $pays){

                if ($authUser->isResponsablePays() && $pays->id == $authPays->id){
                    $paysArr[] = $pays;
                }elseif($authUser->isResponsableSousZone() && $pays->sousZone->id == $authSousZone->id){
                    $paysArr[] = $pays;
                }elseif ($authUser->isResponsableZone() && $pays->sousZone->zone->id == $authZone->id){
                    $paysArr[] = $pays;
                }
            }
        }

        return view('pays.index', compact('paysArr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $sousZones = SousZone::all();

        return view('pays.create', compact('sousZones'));
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
            'continent' => 'required|string',
            'sous_zone_id' => 'required|exists:sous_zones,id',
        ]);

        Pays::create($data);

        return redirect()->route('pays.index')
            ->with('success','Pays créé avec succes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function show(Pays $pays)
    {
        return view('pays.show', compact('pays'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function edit(Pays $pay)
    {
        //
        $sousZones = SousZone::all();
        $pays = $pay;
        return view('pays.edit', compact('pays', 'sousZones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pays $pay)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'continent' => 'required|string',
            'sous_zone_id' => 'required|exists:sous_zones,id'
        ]);

        $pay->update($data);

        return redirect()->route('pays.index')
            ->with('success', 'Pays mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        if(!empty($id)){
            Groupe::where('pays_id', $id)->delete();
            Pays::find($id)->delete();
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
        $pays = Pays::query()->find($request->input('id'));
        if($pays){
            $users =  $pays->getMembres();
        }else{
            abort(404);
        }

        return view('pays.list-members',compact('pays'));
    }

     public function getUsersData(Request $request)
    {
        $pays = Groupe::find($request->input('pays_id'));
        if(!$pays){
            abort(404);
        }
        $users = User::query()
            ->where('id', '!=', 1)
            ->whereHas('activeGroupes.sousZone.pays', function ($query) use ($pays) {
                $query->where('pays.id', $pays->id);
            })
            ->with(['niveauEngagement']);
          
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
}
