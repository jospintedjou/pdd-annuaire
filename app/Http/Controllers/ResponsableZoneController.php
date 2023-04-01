<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\ResponsableZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class ResponsableZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zone::get();
        return view('responsable_zones.index',compact('zones'));
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
     * @param  \App\Models\ResponsableZone  $responsableZone
     * @return \Illuminate\Http\Response
     */
    public function show(ResponsableZone $responsableZone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResponsableZone  $responsableZone
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $zone = Zone::find($request->zone);
        $users = User::where(['etat'=>Constantes::ETAT_ACTIF])->where('role', '!=', Constantes::ROLE_ADMIN)
            ->orderBy('nom')->get();
        if(!empty($request)){
            return view('responsable_zones.edit',compact('zone', 'users'));
        }else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResponsableZone  $responsableZone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResponsableZone $responsableZone)
    {
        $data = $request->validate([
            'zone_id' =>  'required',
            'responsabilite_zones' => 'array',
            'responsable_zone_ids' => 'array'
        ]);

        //$users = User::where(['etat'=>Constantes::ETAT_ACTIF])->get();
        $zone = Zone::find($request->zone_id);

        foreach($request->responsabilite_zones as $key => $responsabiliteZone){
            if($key !== '' ){
                $zone->responsableZones()->where(['nom_responsabilite' => $responsabiliteZone])
                    ->update([ 'actif' => Constantes::ETAT_INACTIF]);

                $zone->responsableZones()->attach($request->responsable_zone_ids[$key], [
                    'nom_responsabilite' => $responsabiliteZone,
                    'actif' => Constantes::ETAT_ACTIF
                ]);
            }
        }

        return redirect()->route('responsable_zones.edit', [$zone])
            ->with('success','Responsables de zone mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResponsableZone  $responsableZone
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResponsableZone $responsableZone)
    {
        //
    }
}
