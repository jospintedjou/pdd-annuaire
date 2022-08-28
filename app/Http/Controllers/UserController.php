<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Apostolat;
use App\Models\Groupe;
use App\Models\GroupeUser;
use App\Models\NiveauEngagement;
use App\Models\ResponsableGroupe;
use App\Models\ResponsableSousZone;
use App\Models\ResponsableZone;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role', '!=', Constantes::ROLE_ADMIN)->latest()->paginate(5);

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
        $niveau_engagements = NiveauEngagement::get();
        $apostolats = Apostolat::get();
        $groupes = Groupe::get();
        $zones = Zone::get();
        $sous_zones = SousZone::get();
        return view('users.create', compact('niveau_engagements', 'apostolats', 'groupes', 'sous_zones', 'zones'));
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
                'nom' =>  'required',
                'prenom' => 'string|nullable',
                'adresse' => 'required',
                'telephone1' => 'required',
                'telephone2' => 'string|nullable',
                'sexe' => 'required',
                'email' => 'string|unique:users',
                'profession' => 'string|nullable',
                'quartier' => 'required',
                'password' => 'string|nullable',
                'niveau_engagement_id' => 'integer|required',
                'categorie_sociale' => 'required',
                'apostolat_id' => 'required',
                'groupe_id' => 'required',
                'etat' => 'required'
            ]);

        $data['role'] = "MEMBRE";
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('passsword'));

        //We are storing the user data in database
        $user = User::create($data);

        //Update old existing User Group (usefull when updating)
        DB::table('groupe_users')->where(['user_id' => $user->id, 'actif' => Constantes::ETAT_ACTIF])
                    ->update(['actif'=>Constantes::ETAT_INACTIF]);
        
        //Store User Group
        GroupeUser::create([
            'groupe_id' => $request->input('groupe_id'),
            'user_id' => $user->id,
            'actif' => Constantes::ETAT_ACTIF
        ]);

        if(!empty($request->input('responsabilite_groupe'))){
            //Check if this group already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'),
                'nom_responsabilite'=>$request->input('responsabilite_groupe')])->delete();

            /* //Check if this group already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            //Check if this group already have a "1er adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'), 'nom_responsabilite'=>Constantes::PREMIER_ADJOINT_RESPONSABLE])
                ->delete();

            //Check if this group already have a "2e adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'), 'nom_responsabilite'=>Constantes::DEUXIEME_ADJOINT_RESPONSABLE])
                ->delete();*/

            ResponsableGroupe::create([
                'groupe_id' => $request->input('responsabilite_groupe_id'),
                'user_id' => $user->id,
                'nom_responsabilite' => $request->input('responsabilite_groupe'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_sous_zone'))){
            //Deletge old responsable sous-zone
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            /*//Check if this sous_zone already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            //Check if this sous_zone already have a "1er adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::PREMIER_ADJOINT_RESPONSABLE])
                ->delete();

            //Check if this sous_zone already have a "2e adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::DEUXIEME_ADJOINT_RESPONSABLE])
                ->delete();
            */

            ResponsableSousZone::create([
                'sous_zone_id' => $request->input('responsable_sous_zone_id'),
                'user_id' => $user->id,
                'nom_responsabilite' => $request->input('responsabilite_sous_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_zone'))){
            //Check if this zone already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>$request->input('responsabilite_zone')])
                ->delete();

            /*
            //Check if this zone already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            //Check if this zone already have a "1er adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>Constantes::PREMIER_ADJOINT_RESPONSABLE])
                ->delete();

            //Check if this zone already have a "2e adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>Constantes::DEUXIEME_ADJOINT_RESPONSABLE])
                ->delete();
            */
            ResponsableZone::create([
                'zone_id' => $request->input('responsable_zone_id'),
                'user_id' => $user->id,
                'nom_responsabilite' => $request->input('responsabilite_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);

        }

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
        $niveau_engagements = NiveauEngagement::get();
        $apostolats = Apostolat::get();
        $groupes = Groupe::get();
        $zones = Zone::get();
        $sous_zones = SousZone::get();
        return view('users.edit', compact('user', 'niveau_engagements',  'apostolats', 'groupes', 'sous_zones', 'zones'));

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
        $data = $request->validate([
            'nom' =>  'required',
            'prenom' => 'string|nullable',
            'adresse' => 'required',
            'telephone1' => 'required',
            'telephone2' => 'string|nullable',
            'sexe' => 'required',
            'email' => 'string|unique:users',
            'profession' => 'string|nullable',
            'quartier' => 'required',
            'password' => 'string|nullable',
            'niveau_engagement_id' => 'integer|required',
            'categorie_sociale' => 'required',
            'apostolat_id' => 'required',
            'groupe_id' => 'required',
            'etat' => 'required'
        ]);

        $data['role'] = "MEMBRE"; //Must be "membre" or "responsable groupe" or "responsable sous-zone" or "responsable zone"
        $data['password'] = $request->filled('passsword') ? \Illuminate\Support\Facades\Hash::make($request->input('passsword')) : $user->password;

        //We are storing the user data in database
        $user = $user->update($data);

        //Update old existing User Group (usefull when updating)
        DB::table('groupe_users')->where(['user_id' => $user->id, 'actif' => Constantes::ETAT_ACTIF])
            ->update(['actif'=>Constantes::ETAT_INACTIF]);

        //Store User Group
        GroupeUser::create([
            'groupe_id' => $request->input('groupe_id'),
            'user_id' => $user->id,
            'actif' => Constantes::ETAT_ACTIF
        ]);

        if(!empty($request->input('responsabilite_groupe'))){
            //Delete old "responsable" groupe
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'),
                'nom_responsabilite'=>$request->input('responsabilite_groupe')])->delete();

            /*//Delete old "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            //Check if this group already have a "1er adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'), 'nom_responsabilite'=>Constantes::PREMIER_ADJOINT_RESPONSABLE])
                ->delete();

            //Check if this group already have a "2e adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_groupes')->where(['groupe_id' => $request->input('responsabilite_groupe_id'), 'nom_responsabilite'=>Constantes::DEUXIEME_ADJOINT_RESPONSABLE])
                ->delete();*/

            ResponsableGroupe::create([
                'groupe_id' => $request->input('responsabilite_groupe_id'),
                'user_id' => $user->id,
                'nom_responsabilite' => $request->input('responsabilite_groupe'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_sous_zone'))){
            //Delete old responsable sous-zone
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'),
                        'nom_responsabilite'=>$request->input('responsable_sous_zone_id')])->delete();

            /* //Check if this sous_zone already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            //Check if this sous_zone already have a "1er adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::PREMIER_ADJOINT_RESPONSABLE])
                ->delete();

            //Check if this sous_zone already have a "2e adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_sous_zones')->where(['sous_zone_id' => $request->input('responsable_sous_zone_id'), 'nom_responsabilite'=>Constantes::DEUXIEME_ADJOINT_RESPONSABLE])
                ->delete(); */

            ResponsableSousZone::create([
                'sous_zone_id' => $request->input('responsable_sous_zone_id'),
                'user_id' => $user->id,
                'nom_responsabilite' => $request->input('responsabilite_sous_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_zone'))){
            //Delete old responsabilite zone. E.g: "RESPONSABLE", "PREMIER_ADJOINT_RESPONSABLE", "DEUXIEME_ADJOINT_RESPONSABLE"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=> $request->input('responsabilite_zone')])
                ->delete();

            /* //Check if this zone already have a "responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>Constantes::RESPONSABLE])
                ->delete();

            //Check if this zone already have a "1er adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>Constantes::PREMIER_ADJOINT_RESPONSABLE])
                ->delete();

            //Check if this zone already have a "2e adjoint au responsable" and throw error if the new value is still "responsable"
            DB::table('responsable_zones')->where(['zone_id' => $request->input('responsable_zone_id'), 'nom_responsabilite'=>Constantes::DEUXIEME_ADJOINT_RESPONSABLE])
                ->delete();*/

            ResponsableZone::create([
                'zone_id' => $request->input('responsable_zone_id'),
                'user_id' => $user->id,
                'nom_responsabilite' => $request->input('responsabilite_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);

        }

       /* $user->update([
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
        ]);*/

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
