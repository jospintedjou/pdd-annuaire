<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\SousZone;
use App\Models\Pays;
use Illuminate\Http\Request;

class PaysController extends Controller
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
        $authPays = $authUser->groupeActif()?->pays();
        $authGroupe = $authUser->groupeActif();
        $paysArr = [];

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $paysArr = Pays::query()->orderby('nom')->get();
        }else{
            $allPays = Pays::query()->with('sousZone')->orderby('nom')->get();
            //Get only user related countries
            foreach($allPays as $pays){

                if ($authUser->isResponsablePays() && $pays->id == $authPays->id){
                    $paysArr[] = $pays;
                }elseif($authUser->isResponsableSousZone() && $pays->sousZone->id == $authSousZone->id){
                    $paysArr[] = $pays;
                }elseif ($authUser->isResponsableZone() && $pays->sousZone->zone->id == $authZone->id){
                    $paysArr[] = $pays;
                }
            }
        }

        return view('pays.index', compact('paysArr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $sousZones = SousZone::all();

        return view('pays.create', compact('sousZones'));
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
            'continent' => 'required|string',
            'sous_zone_id' => 'required|exists:sous_zones,id',
        ]);

        Pays::create($data);

        return redirect()->route('pays.index')
            ->with('success','Pays créé avec succes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function show(Pays $pays)
    {
        return view('pays.show', compact('pays'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function edit(Pays $pay)
    {
        //
        $sousZones = SousZone::all();
        $pays = $pay;
        return view('pays.edit', compact('pays', 'sousZones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pays $pay)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'continent' => 'required|string',
            'sous_zone_id' => 'required|exists:sous_zones,id'
        ]);

        $pay->update($data);

        return redirect()->route('pays.index')
            ->with('success', 'Pays mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pays  $pays
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        if(!empty($id)){
            Groupe::where('pays_id', $id)->delete();
            Pays::find($id)->delete();
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
        $pays = Pays::query()->find($request->input('id'));
        if($pays){
            $users =  $pays->getMembres();
        }else{
            abort(404);
        }

        return view('pays.list-members',compact('users', 'pays'));
    }
}
