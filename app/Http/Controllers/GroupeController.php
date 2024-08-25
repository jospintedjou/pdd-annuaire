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
        $authUser = auth()->user();
        $authZone = $authUser->zone();
        $authSousZone = $authUser->sousZone();
        $authGroupe = $authUser->groupeActif();

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $groupes = Groupe::query()->orderby('nom_groupe')->get();
        }else{
            $allGroupes = Groupe::query()->orderby('nom_groupe')->get();
            //Get only user related groups
            foreach($allGroupes as $groupe){

                if ($authUser->isResponsableGroupe() && $groupe->id == $authGroupe->id){
                    //dd('groupe');
                    $groupes[] = $groupe;
                }elseif ($authUser->isResponsableSousZone() && $groupe->sous_zone_id == $authSousZone->id){
                    $groupes[] = $groupe;
                }elseif ($authUser->isResponsableZone() && $groupe->sousZones()->first()->zone->id == $authZone->id){
                    $groupes[] = $groupe;
                }
            }

        }

        return view('groupes.index', compact('groupes'));
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
            'paroisse' => 'nullable|string',
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
            'paroisse' => 'nullable|string',
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
    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        if(!empty($id)){
            Groupe::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * List all members of the group
     **/
    public function listMembers(Request $request)
    {
        $groupe = Groupe::query()->find($request->input('id'));
        if($groupe){
            $users =  $groupe->getMembres();
        }else{
            abort(404);
        }

        return view('groupes.list-members',compact('users', 'groupe'));
    }
}
