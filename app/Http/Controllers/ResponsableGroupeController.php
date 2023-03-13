<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Groupe;
use App\Models\ResponsableGroupe;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

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
        $groupes = Groupe::get();
        $zones = Zone::get();
        $sous_zones = SousZone::get();
        return view('responsable_groupes.create', compact('groupes', 'sous_zones', 'zones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$data = $request->validate([
            'groupe_id' =>  'required',
            'user_id' => 'required'
        ]);

        //We are storing the user data in database
        $user = User::create($data);

        return redirect()->route('responsable_groupes.index')
            ->with('success','Responsables mis ç jour avec succès.');*/
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
        $users = User::where(['etat'=>Constantes::ETAT_ACTIF])->get();
        if(!empty($request)){
            return view('responsable_groupes.edit',compact('groupe', 'users'));
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
        //
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
