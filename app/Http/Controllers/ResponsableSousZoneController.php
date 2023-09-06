<?php

namespace App\Http\Controllers;
use App\Models\Responsabilite;
use App\Constantes;
use App\Models\ResponsableSousZone;
use App\Models\SousZone;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ResponsableSousZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sous_zones = SousZone::get();
        return view('responsable_sous_zones.index',compact('sous_zones'));
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
     * @param  \App\Models\ResponsableSousZone  $responsableSousZone
     * @return \Illuminate\Http\Response
     */
    public function show(ResponsableSousZone $responsableSousZone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResponsableSousZone  $responsableSousZone
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $sous_zone = SousZone::find($request->sous_zone);
        $responsabilites = Responsabilite::all();
        $users = User::where(['etat'=>Constantes::ETAT_ACTIF])->where('role', '!=', Constantes::ROLE_ADMIN)
            ->orderBy('nom')->get();
        if(!empty($request)){
            return view('responsable_sous_zones.edit',compact('sous_zone', 'users', 'responsabilites'));
        }else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResponsableSousZone  $responsableSousZone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResponsableSousZone $responsableSousZone)
    {
        $data = $request->validate([
            'sous_zone_id' =>  'required',
            'responsabilite_sous_zones' => 'array'
        ]);

        //$users = User::where(['etat'=>Constantes::ETAT_ACTIF])->get();
        $sous_zone = SousZone::find($request->sous_zone_id);

        foreach($request->responsabilite_sous_zones as $responsabiliteId => $responsableId){
            if($responsabiliteId){
                $sous_zone->responsableSousZones()->where(['responsabilite_id' => $responsabiliteId])
                    ->update([ 'actif' => Constantes::ETAT_INACTIF]);
                if($responsableId){
                    //We are saving the new responsability for the group
                    $sous_zone->responsableSousZones()->attach($responsableId, [
                        'responsabilite_id' => $responsabiliteId,
                        'actif' => Constantes::ETAT_ACTIF
                    ]);
                }
            }
        }

        return redirect()->route('responsable_sous_zones.edit', [$sous_zone])
            ->with('success','Responsables de Sous-zone mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResponsableSousZone  $responsableSousZone
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResponsableSousZone $responsableSousZone)
    {
        //
    }
}
