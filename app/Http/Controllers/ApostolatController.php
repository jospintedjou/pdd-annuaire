<?php

namespace App\Http\Controllers;

use App\Models\Apostolat;
use Illuminate\Http\Request;

class ApostolatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apostolats = Apostolat::latest()->paginate(5);

        return view('apostolats.index',compact('apostolats'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apostolats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
        ]);

        Apostolat::create($request->all());

        return redirect()->route('apostolats.index')
            ->with('success','Apostolat créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function show(Apostolat $apostolat)
    {
        return view('apostolats.show',compact('apostolat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function edit(Apostolat $apostolat)
    {
        return view('apostolats.edit',compact('apostolat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apostolat $apostolat)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'string'
        ]);

        $apostolat->update($request->all());

        return redirect()->route('apostolats.index')
            ->with('success','Apostolat updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apostolat  $apostolat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apostolat $apostolat)
    {
        $apostolat->delete();

        return redirect()->route('apostolats.index')
            ->with('success','Apostolat deleted successfully');
    }
}
