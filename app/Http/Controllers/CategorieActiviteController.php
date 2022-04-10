<?php

namespace App\Http\Controllers;

use App\Models\CategorieActivite;
use Illuminate\Http\Request;

class CategorieActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categorieActivites = CategorieActivite::latest()->paginate(5);

        return view('categorie_activites.index', compact('categorieActivites'))
                    ->with('i', (request()->input('page', 1)-1)*5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('categorie_activites.create');
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
            'periodicite' => 'required|string'
        ]);

        CategorieActivite::create($data);

        return redirect()->route('categorie_activites.index')
                ->with('message', 'Categorie créé avec succes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategorieActivite  $categorieActivite
     * @return \Illuminate\Http\Response
     */
    public function show(CategorieActivite $categorieActivite)
    {
        //
        return view('categorie_activites.show', compact('categorieActivite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategorieActivite  $categorieActivite
     * @return \Illuminate\Http\Response
     */
    public function edit(CategorieActivite $categorieActivite)
    {
        //
        return view('categorie_activites.edit', compact('categorieActivite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategorieActivite  $categorieActivite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategorieActivite $categorieActivite)
    {
        //

        $data = $request->validate([
            'nom' => 'required|string',
            'periodicite' => 'required|string'
        ]);

        $categorieActivite->update($data);

        return redirect()->route('categorie_activites.index')
            ->with('message', 'Categorie modifié avec success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategorieActivite  $categorieActivite
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategorieActivite $categorieActivite)
    {
        //
        $categorieActivite->delete();

        return redirect()->route('categorie_activites.index')
                ->with('message', 'Categorie Activite supprimé avec succes');
    }
}
