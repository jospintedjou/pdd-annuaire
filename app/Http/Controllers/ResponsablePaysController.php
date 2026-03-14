<?php

namespace App\Http\Controllers;
use App\Models\Responsabilite;
use App\Constantes;
use App\Models\ResponsablePays;
use App\Models\Pays;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ResponsablePaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = auth()->user();
        $authSousZone = $authUser->sousZone();
        $authPays = $authUser->pays();
        $authGroupe = $authUser->groupeActif();

        //Only the admin can see all the activities. Normal user sees the Pays activities.
        if($authUser->isAdmin()){
            $paysArr = Pays::query()->with('responsablePays')->get();
        }elseif($authPays !== null){
            $allPays = Pays::query()->with('responsablePays')->get();
            $paysArr = [];
            foreach($allPays as $pays){
                if ( $pays->sous_zone_id == $authPays->sous_zone_id){
                    $paysArr[] = $pays;
                }
            }
        }else{
            // Regular member with no pays assigned: show nothing
            $paysArr = [];
        }
        return view('responsable_pays.index',compact('paysArr'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResponsablePays  $responsablePays
     * @return \Illuminate\Http\Response
     */
    public function show(ResponsablePays $responsablePays)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResponsablePays  $responsablePays
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $pays = Pays::find($request->pays);
        if(empty($pays)) abort(404);
        $responsabilites = Responsabilite::all();
        $currentResponsables = $pays->responsablePays()
            ->where('actif', Constantes::ETAT_ACTIF)
            ->get()->keyBy('pivot.responsabilite_id');
        $initialUsers = User::where('etat', Constantes::ETAT_ACTIF)
            ->where('role', '!=', Constantes::ROLE_ADMIN)
            ->orderBy('nom')->limit(10)->get(['id', 'nom', 'prenom']);
        return view('responsable_pays.edit', compact('pays', 'initialUsers', 'responsabilites', 'currentResponsables'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResponsablePays  $responsablePays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResponsablePays $responsablePays)
    {
        $data = $request->validate([
            'pays_id' =>  'required',
            'responsabilite_pays' => 'array'
        ]);

        //$users = User::where(['etat'=>Constantes::ETAT_ACTIF])->get();
        $pays = Pays::find($request->pays_id);

        foreach($request->responsabilite_pays as $responsabiliteId => $responsableId){
            if($responsabiliteId){
                $pays->responsablePays()->where(['responsabilite_id' => $responsabiliteId])
                    ->update([ 'actif' => Constantes::ETAT_INACTIF]);
                if($responsableId){
                    //We are saving the new responsability for the group
                    $pays->responsablePays()->attach($responsableId, [
                        'responsabilite_id' => $responsabiliteId,
                        'actif' => Constantes::ETAT_ACTIF
                    ]);
                }
            }
        }
        
        return redirect()->route('responsable_pays.edit', [$pays])
            ->with('success','Responsables de pays mis à jour avec succès.')
            ->with('success_link', route('responsable_pays.index'))
            ->with('success_link_text', 'Voir la liste des responsables');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResponsablePays  $responsablePays
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResponsablePays $responsablePays)
    {
        //
    }
}
