<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NiveauEngagement;

class NiveauEngagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $niveau_engagements = NiveauEngagement::get();

        return view('niveau_engagements.index',compact('niveau_engagements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('niveau_engagements.create');
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

        NiveauEngagement::create($request->all());

        return redirect()->route('niveau_engagements.index')
            ->with('success','Niveau d\'engagement créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NiveauEngagement  $niveau_engagement
     * @return \Illuminate\Http\Response
     */
    public function show(NiveauEngagement $niveau_engagement)
    {
        return view('niveau_engagements.show',compact('niveau_engagement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NiveauEngagement  $niveau_engagement
     * @return \Illuminate\Http\Response
     */
    public function edit(NiveauEngagement $niveau_engagement)
    {
        return view('niveau_engagements.edit',compact('niveau_engagement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NiveauEngagement  $niveau_engagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NiveauEngagement $niveau_engagement)
    {
        $request->validate([
            'nom' => 'required'
        ]);

        $niveau_engagement->update($request->all());

        return redirect()->route('niveau_engagements.index')
            ->with('success','Niveau d\'engagement mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NiveauEngagement  $niveau_engagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id)){
            NiveauEngagement::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }


    }
}
