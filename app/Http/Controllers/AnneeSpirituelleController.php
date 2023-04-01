<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\AnneeSpirituelle;
use Illuminate\Http\Request;

class AnneeSpirituelleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $annee_spirituelles = AnneeSpirituelle::get();

        return view('annee_spirituelles.index',compact('annee_spirituelles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('annee_spirituelles.create');
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
            'nom' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date'
        ]);

        $data['etat'] = Constantes::ETAT_ACTIF;

        AnneeSpirituelle::create($data);

        return redirect()->route('annee_spirituelles.index')
            ->with('success','année spirituelle créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnneeSpirituelle  $anneeSpirituelle
     * @return \Illuminate\Http\Response
     */
    public function show(AnneeSpirituelle $annee_spirituelle)
    {
        return view('annee_spirituelles.show',compact('annee_spirituelle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnneeSpirituelle  $anneeSpirituelle
     * @return \Illuminate\Http\Response
     */
    public function edit(AnneeSpirituelle $annee_spirituelle)
    {
        return view('annee_spirituelles.edit',compact('annee_spirituelle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnneeSpirituelle  $anneeSpirituelle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnneeSpirituelle $anneeSpirituelle)
    {
        $request->validate([
            'nom' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
        ]);

        $anneeSpirituelle->update($request->all());

        return redirect()->route('annee_spirituelles.index')
            ->with('success','Année spirituelles mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnneeSpirituelle  $anneeSpirituelle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id)){
            AnneeSpirituelle::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }
}
