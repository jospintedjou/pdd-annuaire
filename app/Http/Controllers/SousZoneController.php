<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\Pays;
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
        $authUser = auth()->user();
        $authZone = $authUser->zone();
        $authSousZone = $authUser->sousZone();
        $authGroupe = $authUser->groupeActif();
        $sous_zones = [];

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $sous_zones = SousZone::query()->orderby('nom')->get();
        }else{
            $allSousZones = SousZone::query()->orderby('nom')->get();
            //Get only user related groups
            foreach($allSousZones as $sousZone){

                if ($authUser->isResponsableSousZone() && $sousZone->id == $authSousZone->id){
                    $sous_zones[] = $sousZone;
                }elseif ($authUser->isResponsableZone() && $sousZone->zone->id == $authZone->id){
                    $sous_zones[] = $sousZone;
                }
            }
        }

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
            'has_country' => 'required|boolean',
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
            'has_country' => 'required|boolean',
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
            Groupe::where('sous_zone_id', $id)->delete();
            SousZone::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * List all members of the zone
     **/
    public function listMembers(Request $request)
    {
        $sousZone = SousZone::query()->find($request->input('id'));
        if($sousZone){
            $users =  $sousZone->getMembres();
        }else{
            abort(404);
        }

        return view('sous_zones.list-members',compact('users', 'sousZone'));
    }

    /**
     * Return all 'pays'' of a sous-zone by id.
     *
     * @param  \App\Models\SousZone  $sousZone
     * @return \Illuminate\Http\Response
     */
    public function getPays(Request $request)
    {

        $str = "";
        $sous_zone_id = $request->input('sous_zone_id');
        $countries = Pays::where('sous_zone_id', $sous_zone_id)->get();

        if(!$countries->isEmpty()){
            $str .= "<option value='' disabled selected>Choisissez un pays</option>";
            foreach($countries as $country){
                $str .= "<option value=".$country->id.">".$country->nom."</option>";
            }
        }else{
            $str = "<option value=''>Aucun pays trouvé</option>";
        }

        return response()->json(['status'=>'success', 'data'=>$str], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

}
