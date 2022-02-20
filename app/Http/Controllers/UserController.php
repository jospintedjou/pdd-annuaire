<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(5);

        return view('users.index',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
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
            'title' => 'required',
            'description' => 'required',
        ]);
        //1. Store user
        //2. Store Apostolat
        //3. Store Niveau Engagement
        User::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'adresse' => $request->input('adresse'),
            'telephone1' => $request->input('telephone1'),
            'telephone2' => $request->input('telephone2'),
            'sexe' => $request->input('sexe'),
            'date_naissance' => $request->input('date_naissance'),
            'email' => $request->input('email'),
            'profession' => $request->input('profession'),
            'pays' => $request->input('pays'),
            'ville' => $request->input('ville'),
            'quartier' => $request->input('quartier'),
            'niveau_engagement_id' => $request->input('niveau_engagement_id'),
            'role' => $request->input('role'),
            'categorie_sociale' => $request->input('categorie_sociale'),
            'apostolat_id' => $request->input('apostolat_id'),
        ]);

        return redirect()->route('users.index')
            ->with('success','Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'string',
            'adresse' => 'required',
            'telephone1' => 'string',
            'telephone2' => 'string',
            'sexe' => 'string',
            'date_naissance' => 'string',
            'email' => 'string',
            'profession' => 'string',
            'pays' => 'required',
            'ville' => 'required',
            'quartier' => 'required',
            'niveau_engagement_id' => 'required',
            'role' => 'string',
            'categorie_sociale' => 'required',
            'apostolat_id' => 'required',
        ]);

        $user->update([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'adresse' => $request->input('adresse'),
            'telephone1' => $request->input('telephone1'),
            'telephone2' => $request->input('telephone2'),
            'sexe' => $request->input('sexe'),
            'date_naissance' => $request->input('date_naissance'),
            'email' => $request->input('email'),
            'profession' => $request->input('profession'),
            'pays' => $request->input('pays'),
            'ville' => $request->input('ville'),
            'quartier' => $request->input('quartier'),
            'niveau_engagement_id' => $request->input('niveau_engagement_id'),
            'role' => $request->input('role'),
            'categorie_sociale' => $request->input('categorie_sociale'),
            'apostolat_id' => $request->input('apostolat_id'),
        ]);

        return redirect()->route('users.index')
            ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if(!empty($id)){
            Apostolat::find($id)->delete();
            return response()->json(['status'=>'success'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>'error'], 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }

    }
}
