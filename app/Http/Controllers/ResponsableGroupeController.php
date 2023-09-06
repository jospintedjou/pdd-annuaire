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
        $groupes = Groupe::get();
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
        $responsabilites = Responsabilite::all();
        $users = User::where(['etat'=>Constantes::ETAT_ACTIF])->where('role', '!=', Constantes::ROLE_ADMIN)
                    ->orderBy('nom')->get();
        if(!empty($request)){
            return view('responsable_groupes.edit',compact('groupe', 'users', 'responsabilites'));
        }else{
            abort(404);
        }

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
            ->with('success','Responsables de groupe mis à jour avec succès.');
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
