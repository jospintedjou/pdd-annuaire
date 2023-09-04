<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Apostolat;
use App\Models\Evaluation;
use App\Http\Requests\UpdateEvaluationRequest;
use App\Models\Groupe;
use App\Models\NiveauEngagement;
use App\Models\Rubrique;
use App\Models\SousZone;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{

    /**
     * Show all activities so that user can go further and record evaluation
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rubriques = Rubrique::get();

        return view('evaluations.index', compact('rubriques'));
    }

    //Show list of 'rubriques' in a table with checkboxes
    public function create(Request $request)
    {
        $rubrique = '';
        if($request->rubrique){
            $rubrique = Rubrique::find($request->rubrique);
        }
        if(!$rubrique || !$request->rubrique){
            abort(404);
        }

        $users = User::where('role', '!=', Constantes::ROLE_ADMIN)->get();

        $niveau_engagements = NiveauEngagement::get();
        $apostolats = Apostolat::orderBy('nom')->get();
        $groupes = Groupe::orderBy('nom_groupe')->get();
        $zones = Zone::orderBy('nom')->get();
        $sous_zones = SousZone::orderBy('nom')->get();

        return view('evaluations.create', compact('rubrique', 'users', 'niveau_engagements', 'apostolats',
            'groupes', 'sous_zones', 'zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rubrique_id' => 'required|exists:rubriques,id',
            'user_id' => 'required|exists:users,id',
            'evaluation' => 'required'
        ]);

        //Delete existing user's evaluation for this specific activity
        $evaluation = Evaluation::where(['rubrique_id' => $request->rubrique_id,
                            'user_id' => $request->user_id])->forceDelete();

        //Add to database if line is checked
        if($request->evaluation){
            Evaluation::create($data);
        }

        return response()->json(['status'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */
    public function show(Evaluation $evaluation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEvaluationRequest  $request
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evaluation $evaluation)
    {
        //
    }
}
