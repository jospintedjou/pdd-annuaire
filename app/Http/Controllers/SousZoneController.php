<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\SousZone;
use Illuminate\Http\Request;

class SousZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sous_zones = SousZone::latest()->paginate();

        return view('sous_zones.index', compact('sous_zones'))
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
            'zone' => 'required|exists:zones,id',
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
    public function edit(SousZone $sousZone)
    {
        //
        return view('sous_zones', compact('sousZone'));
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
            'zone' => 'required|exists:zones,id',
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
    public function destroy(SousZone $sousZone)
    {
        //

        $sousZone->delete();

        return redirect()->route('sous_zones.index')
            ->with('success', 'Sous Zone deleted successfully');
    }
}
