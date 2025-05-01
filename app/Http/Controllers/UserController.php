<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Apostolat;
use App\Models\ApostolatUser;
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
use \Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use Maatwebsite\Excel\HeadingRowImport;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query()->where('id', '!=', 1)->orderby('nom', 'asc')->get();

        return view('users.index',compact('users'));
    }

    /**
     * Save imported data from Excel File.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request){
        $data = $request->validate([
            'file' =>  'required||mimes:xls,xlsx,csv',
        ]);

        $updateFile = $request->file('file');
        $path = $updateFile->getRealPath();
        //$path = $updateFile->getClientOriginalName();

        $originalHeadings = (new HeadingRowImport(1))->toArray($path);
        $originalHeadings = array_change_key_case($originalHeadings, CASE_LOWER)[0][0];

        $headings_arr =  ["zone", "sous_zone", "groupe", "noms", "prenoms", "sexe",
                    "apostolat", "categorie","niveau_dengagement", "profession_classe",
                    "specialite_filiere", "ville", "quartier",
                    "telephone_whatsapp","email"
        ];

        //Check if the excel file has all needed headings
        $fileHasError = 0;
        $fileErrors = "";
        foreach($headings_arr as $heading){
            if(!in_array($heading, $originalHeadings)){
                $fileErrors .= "Le fichier ne contient pas la colonne $heading \n";
            }
        }

        if($fileHasError){
            throw ValidationException::withMessages(['file' => $fileErrors]);
        }

        //$datas = Excel::import(new ProductsImport($request->suppliers_id),request()->file('file'));

        try {
            $importUser = new ImportUser;

            $excelData = Excel::import($importUser,
                            $request->file('file')->store('files'));

            $totalImportedRows = $importUser->getImportedCount();
            $failures = $importUser->failures();

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            /*foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }*/
        }

        $duplicatedRows = $importUser->getDuplicatedRows();
        $duplicatedRowsStr = implode(', ', $duplicatedRows);
        $countRows = count($duplicatedRows);

        $res = redirect()->back()
                ->with('totalImportedRows', $totalImportedRows);

        $totalFailures = isset($failures) ? count($failures) : 0;

        if($duplicatedRowsStr != ""){
            $res->with('duplicatedRowsStr',
            $countRows.' noms en double : '.$duplicatedRowsStr);
        }
        
        $res = !empty($failures)
                ? $res->with('success',
                    $totalImportedRows.' membres ajouté(s) avec succès.')
                : $res->with('error',
                    $totalImportedRows.' membres ajouté(s), '.$totalFailures.' lignes ignorré(e)s')
                    ->with('failures',$failures);

        return $res;

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
        $zones = Zone::get();
        $sous_zones = SousZone::get();

        $authUser = auth()->user();
        $authZone = $authUser->zone();
        $authSousZone = $authUser->sousZone();
        $authGroupe = $authUser->groupeActif();

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $groupes = Groupe::query()->orderby('nom_groupe')->get();
        }else{
            $allGroupes = Groupe::query()->orderby('nom_groupe')->get();
            $groupes = [];
            foreach($allGroupes as $groupe){
                if ( $authUser->isResponsableZone() && $groupe->sousZone->zone->id == $authZone->id){
                    $groupes[] = $groupe;
                }elseif( $authUser->isResponsableSousZone() && $groupe->sousZone->id == $authSousZone->id){
                    $groupes[] = $groupe;
                }elseif( $authUser->isResponsableGroupe() && $groupe->id == $authGroupe->id){
                    $groupes[] = $groupe;
                }
            }
        }

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
                'email' => 'string|nullable|unique:users',
                'profession' => 'string|nullable',
                'quartier' => 'required',
                'password' => 'string|nullable',
                'niveau_engagement_id' => 'integer|required',
                'categorie_sociale' => 'required',
                'groupe_id' => 'required',
                'etat' => 'required',
                'apostolat_id' => 'required|array|min:1',
                'apostolat_id.*' => 'exists:apostolats,id',
                'date_entree' => 'date|nullable',
            ]);

        $data['role'] = Constantes::ROLE_MEMBRE;
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('password'));

        //We are storing the user data in database
        $user = User::create($data);

        //Update old existing User Group (usefull when updating)
        DB::table('groupe_user')->where(['user_id' => $user->id, 'actif' => Constantes::ETAT_ACTIF])
                    ->update(['actif'=>Constantes::ETAT_INACTIF]);
        
        //Store User Group
        $user->groupes()->attach($request->input('groupe_id'), [
            'actif' => Constantes::ETAT_ACTIF
        ]);

        //Store User Apostolats
        DB::table('apostolat_user')->where(['user_id' => $user->id])->delete();
        foreach($request->input('apostolat_id') as $apostolat_id){
            $user->apostolats()->attach([
                'apostolat_id' => $apostolat_id
            ]);
        }

        /*
        if(!empty($request->input('responsabilite_groupe'))){
            //Check if this group already have a "responsable" and throw error if the new value is still "responsable"$user->responsableZones()->where(['zone_id' => $request->input('responsable_zone_id')])

            $user->responsableGroupes()->where(['groupe_id' => $request->input('responsabilite_groupe_id')])
                ->update([ 'actif' => Constantes::ETAT_INACTIF]);

            $user->responsableGroupes()->attach($request->input('responsabilite_groupe_id'), [
                'nom_responsabilite' => $request->input('responsabilite_groupe'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_sous_zone'))){
            //Deletge old responsable sous-zone

            $user->responsableSousZones()->where(['sous_zone_id' => $request->input('responsabilite_sous_zone_id')])
                ->update([ 'actif' => Constantes::ETAT_INACTIF]);

            $user->responsableSousZones()->attach($request->input('responsable_sous_zone_id'), [
                'nom_responsabilite' => $request->input('responsabilite_sous_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_zone'))){
            //Check if this zone already have a "responsable" and throw error if the new value is still "responsable"
            $user->responsableZones()->where(['zone_id' => $request->input('responsabilite_zone_id')])
                ->update([ 'actif' => Constantes::ETAT_INACTIF]);

            $user->responsableZones()->attach($request->input('responsable_zone_id'), [
                'nom_responsabilite' => $request->input('responsabilite_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        */

        if($request->filled('to') && $request->filled('activite_id')){
            return redirect()->route('presences.create', ['activite' =>$request->activite_id])
                    ->with('success','Utilisateur créé avec succès.');
        }else{
            return redirect()->route('users.index')
                    ->with('success','Utilisateur créé avec succès.');
        }

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
        $groupes = [];
        $zones = Zone::get();
        $sous_zones = SousZone::get();

        $authUser = auth()->user();
        $authZone = $authUser->zone();
        $authSousZone = $authUser->sousZone();
        $authGroupe = $authUser->groupeActif();

        //Only the admin can see all the activities. Normal user sees the zone activities.
        if($authUser->isAdmin()){
            $groupes = Groupe::query()->orderby('nom_groupe')->get();
        }else{
            $allGroupes = Groupe::query()->orderby('nom_groupe')->get();
            $groupes = [];
            foreach($allGroupes as $groupe){
                if ( $authUser->isResponsableZone() && $groupe->sousZone->zone->id == $authZone->id){
                    $groupes[] = $groupe;
                }elseif( $authUser->isResponsableSousZone() && $groupe->sousZone->id == $authSousZone->id){
                    $groupes[] = $groupe;
                }elseif( $authUser->isResponsableGroupe() && $groupe->id == $authGroupe->id){
                    $groupes[] = $groupe;
                }
            }
        }

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
            'email' => 'nullable|max:50|email|unique:users,email,'.$user->id,
            'profession' => 'string|nullable',
            'quartier' => 'required',
            'password' => 'string|nullable',
            'niveau_engagement_id' => 'integer|required',
            'categorie_sociale' => 'required',
            'apostolat_id' => 'required|array|min:1',
            'apostolat_id.*' => 'exists:apostolats,id',
            'groupe_id' => 'required',
            'etat' => 'required',
            'date_entree' => 'date|nullable'
        ]);

        $data['role'] = "MEMBRE"; //Must be "membre" or "responsable groupe" or "responsable sous-zone" or "responsable zone"
        $data['password'] = $request->filled('password') ? \Illuminate\Support\Facades\Hash::make($request->input('password')) : $user->password;

        //We are storing the user data in database
        $user->update($data);

        //Update old existing User Group (usefull when updating)
        DB::table('groupe_user')->where(['user_id' => $user->id, 'actif' => Constantes::ETAT_ACTIF])
            ->update(['actif'=>Constantes::ETAT_INACTIF]);

        //Store User Group
        $user->groupes()->attach($request->input('groupe_id'),[
            'actif' => Constantes::ETAT_ACTIF
        ]);

        //Store User Apostolats
        DB::table('apostolat_user')->where(['user_id' => $user->id])->delete();

        foreach($request->input('apostolat_id') as $apostolat_id){
            $user->apostolats()->attach([
                'apostolat_id' => $apostolat_id
            ]);
        }

        if(!empty($request->input('responsabilite_groupe'))){
            //Delete old "responsable" groupe

            $user->responsableGroupes()->where(['groupe_id' => $request->input('responsabilite_groupe_id')])
                ->update([ 'actif' => Constantes::ETAT_INACTIF]);

            $user->responsableGroupes()->attach($request->input('responsabilite_groupe_id'), [
                'nom_responsabilite' => $request->input('responsabilite_groupe'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_sous_zone'))){
            //Delete old responsable sous-zone
            $user->responsableSousZones()->where(['sous_zone_id' => $request->input('responsable_sous_zone_id')])
                ->update([ 'actif' => Constantes::ETAT_INACTIF]);

            $user->responsableSousZones()->attach($request->input('responsable_sous_zone_id'), [
                'nom_responsabilite' => $request->input('responsabilite_sous_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);
        }
        if(!empty($request->input('responsabilite_zone'))){
            //Delete old responsabilite zone. E.g: "RESPONSABLE", "PREMIER_ADJOINT_RESPONSABLE", "DEUXIEME_ADJOINT_RESPONSABLE"
            $user->responsableZones()->where(['zone_id' => $request->input('responsable_zone_id')])
                ->update([ 'actif' => Constantes::ETAT_INACTIF]);

            $user->responsableZones()->attach($request->input('responsable_zone_id'), [
                'nom_responsabilite' => $request->input('responsabilite_zone'),
                'actif' => Constantes::ETAT_ACTIF
            ]);

        }

        return redirect()->route('users.index')
            ->with('success','Utilisateur mis à jour avec succès');
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
