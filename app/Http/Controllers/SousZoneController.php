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
        $sous_zones = SousZone::get();

        return view('sous_zones.index', compact('sous_zones'));
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
            'zone_id' => 'required|exists:zones,id',
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
    public function edit(SousZone $sous_zone)
    {
        //
        $zones = Zone::all();

        return view('sous_zones.edit', compact('sous_zone'), compact('zones'));
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
            'zone_id' => 'required|exists:zones,id',
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
    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        if(!empty($id)){
            SousZone::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }
}
