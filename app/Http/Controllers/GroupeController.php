<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Groupe;
use App\Models\SousZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupes = Groupe::latest()->paginate(5);

        return view('groupes.index', compact('groupes'))
            ->with('i', (request()->input('page', 1)-1)*5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zone::all();
        $sous_zones = SousZone::all();

        return view('groupes.create', compact('zones'), compact('sous_zones'));
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
            'nom_groupe' => 'required|string',
            'paroisse' => 'required|string',
            'jour_reunion' => 'required|string',
            'heure_reunion' => 'required',
            'sous_zone_id' => 'required|exists:sous_zones,id'
        ]);

        Groupe::create($data);

        return redirect()->route('groupes.index')
            ->with('success', 'Groupe created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Groupe  $groupe
     * @return \Illuminate\Http\Response
     */
    public function show(Groupe $groupe)
    {
        return view('groupes.show', compact('groupe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Groupe  $groupe
     * @return \Illuminate\Http\Response
     */
    public function edit(Groupe $groupe)
    {
        $zones = Zone::all();
        $sous_zones = SousZone::all();

        return view('groupes.edit', compact('groupe'), compact('zones'))->with('sous_zones', $sous_zones);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Groupe  $groupe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Groupe $groupe)
    {
        $data = $request->validate([
            'nom_groupe' => 'required|string',
            'paroisse' => 'required|string',
            'jour_reunion' => 'required|string',
            'heure_reunion' => 'required',
            'sous_zone_id' => 'required|exists:sous_zones,id'
        ]);

        $groupe->update($data);

        return redirect()->route('groupes.index')
            ->with('success', 'Groupe updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Groupe  $groupe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Groupe $groupe)
    {
        $groupe->delete();

        return redirect()->route('groupes.index')
            ->with('success', 'Groupe deleted successfully');
    }
}
