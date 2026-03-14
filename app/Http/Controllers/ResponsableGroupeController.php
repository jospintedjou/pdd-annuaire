<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Groupe;
use App\Models\ResponsableGroupe;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Models\Responsabilite;
class ResponsableGroupeController extends Controller
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

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $groupes = Groupe::query()->get();
        }else{
            $allGroupes = Groupe::query()->get();
            $groupes = [];
            foreach($allGroupes as $groupe){
                if ( $groupe->sousZone->zone_id == $authZone->id){
                    $groupes[] = $groupe;
                }
            }
        }
        return view('responsable_groupes.index',compact('groupes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResponsableGroupe  $responsableGroupe
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResponsableGroupe  $responsableGroupe
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $groupe = Groupe::find($request->groupe);
        if(empty($groupe)){
            abort(404);
        }
        $responsabilites = Responsabilite::all();

        // Preload current active responsables keyed by responsabilite_id — fixes N+1
        $currentResponsables = $groupe->responsableGroupes()
            ->where('actif', Constantes::ETAT_ACTIF)
            ->get()
            ->keyBy('pivot.responsabilite_id');

        // Initial 10 users for page load (AJAX will load the rest on search/scroll)
        $initialUsers = User::where('etat', Constantes::ETAT_ACTIF)
            ->where('role', '!=', Constantes::ROLE_ADMIN)
            ->orderBy('nom')
            ->limit(10)
            ->get(['id', 'nom', 'prenom']);

        return view('responsable_groupes.edit', compact('groupe', 'initialUsers', 'responsabilites', 'currentResponsables'));
    }

    /**
     * AJAX endpoint: search users by name (used by Select2)
     */
    public function searchUsers(Request $request)
    {
        $q = trim($request->input('q', ''));
        $page = (int) $request->input('page', 1);
        $perPage = 10;

        $query = User::where('etat', Constantes::ETAT_ACTIF)
            ->where('role', '!=', Constantes::ROLE_ADMIN)
            ->orderBy('nom');

        if ($q !== '') {
            $query->where(function($sub) use ($q) {
                $sub->where('nom', 'LIKE', '%'.$q.'%')
                    ->orWhere('prenom', 'LIKE', '%'.$q.'%');
            });
        }

        $total  = $query->count();
        $users  = $query->skip(($page - 1) * $perPage)->take($perPage)->get(['id', 'nom', 'prenom']);

        return response()->json([
            'results'    => $users->map(fn($u) => ['id' => $u->id, 'text' => $u->nom.' '.$u->prenom]),
            'pagination' => ['more' => ($page * $perPage) < $total],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResponsableGroupe  $responsableGroupe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResponsableGroupe $responsableGroupe)
    {

        $data = $request->validate([
            'groupe_id' =>  'required',
            'responsabilite_groupes' => 'array'
        ]);

        //$users = User::where(['etat'=>Constantes::ETAT_ACTIF])->get();
        $groupe = Groupe::find($request->groupe_id);

        foreach($request->responsabilite_groupes as $responsabiliteId => $responsableId){
            if($responsabiliteId){
                //If the chosen responsability was used in that group, we just disable it
                $groupe->responsableGroupes()->where(['responsabilite_id' => $responsabiliteId])
                    ->update([ 'actif' => Constantes::ETAT_INACTIF]);

                if($responsableId){
                    //We are saving the new responsability for the group
                    $groupe->responsableGroupes()->attach($responsableId, [
                        'responsabilite_id' => $responsabiliteId,
                        'actif' => Constantes::ETAT_ACTIF
                    ]);
                }

            }
        }

        return redirect()->route('responsable_groupes.edit', [$groupe])
            ->with('success','Responsables de groupe mis à jour avec succès.')
            ->with('success_link', route('responsable_groupes.index'))
            ->with('success_link_text', 'Voir la liste des responsables');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResponsableGroupe  $responsableGroupe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
