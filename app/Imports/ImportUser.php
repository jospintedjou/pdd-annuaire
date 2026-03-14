<?php

namespace App\Imports;

use App\Constantes;
use App\Models\Apostolat;
use App\Models\Groupe;
use App\Models\NiveauEngagement;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportUser implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{

    use Importable, SkipsFailures;

    private $importedCount = 0;

    private $currentRow = 1;

    private $duplicatedRows = [];

    /**
     * @param  int $headingRow
     */
    public function __construct()
    {
    }

    public function rules(): array
    {
       return [
            /*
             '*.groupe' => ['required', 'exists:groupes,nom_groupe'],

            '*.niveau_dengagement' => ['required', 'exists:niveau_engagements,nom'],
            */


           /*'*.nom' => ['required'],*/
           /*'*.sexe' => ['required', 'in:'.Constantes::SEXE_MASCULIN.','.Constantes::SEXE_FEMININ],*/
            /*'*.profession_classe' => ['required', 'min:2'],*/
        ];

        /*
         return [
            '*.zone' => Rule::exists('zones', 'nom'),
            'sous_zone' => Rule::exists('sous_zones', 'nom'),
            'groupe' => Rule::exists('groupes', 'nom'),
            'noms' => Rule::uniqueUser('noms', 'prenoms'),
            'sexe' => Rule::in([Constantes::SEXE_MASCULIN, Constantes::SEXE_FEMININ]),
            'niveau_dengagement_2023' => Rule::exists('sous_zones', 'nom'),
            'profession_classe' => Rule::exists('sous_zones', 'nom'),
        ];
        */
    }

    public function onFailure(Failure ...$failures)
    {
        // Handle each failure
        foreach ($failures as $failure) {
            // Access failure details
            $row = $failure->row(); // Row number
            $attribute = $failure->attribute(); // Column name or index
            $errors = $failure->errors(); // Validation error messages
            $values = $failure->values(); // The row's data

            // Log or store the failure details as needed
        }
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //Validate file header

        //Skip empty rows in the provided Excel File
        if(empty($row['zone']) || empty($row['sous_zone'])  || empty($row['groupe']) ) {
            Log::info('Row N° '. $this->currentRow.' skipped in Excel file because the zone, sous-zone or group name is empty');
            $this->currentRow++;
            return null;
         } 
        $row['zone'] = $row['zone'] == "ZONE DU RESPONSABLE GÉNÉRAL" 
                        ? Constantes::ZONE_RESPONSABLE_GENERAL : $row['zone'];

        $nom = empty($row['noms']) ? 'ras' : $row['noms'];
        $prenoms = empty($row['prenoms']) ? 'ras' : $row['prenoms'];
        $niveau_engagement = NiveauEngagement::where('nom', $row['niveau_dengagement'])->first();
        $niveau_engagement_id = $niveau_engagement ? $niveau_engagement->id : NULL;
        $zone = Zone::where('nom', $row['zone'])->first();
        $sousZone = SousZone::where('nom', $row['sous_zone'])->first();
        $groupe = Groupe::where('nom_groupe', $row['groupe'])->first();
        $groupe_id = $groupe ? $groupe->id : NULL;
        $sexe = $row['sexe'] == "Masculin" || $row['sexe'] == "M" ? Constantes::SEXE_MASCULIN : Constantes::SEXE_FEMININ;
        $quartier = empty($row['quartier']) ? 'ras' : $row['quartier'];

        if(empty($groupe)) {
            Log::info('Row skipped in Excel file because the group name ' . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->currentRow++;
            return null;
        }

        //If not specified the engagement level is 'regulier'
        if(empty($niveau_engagement)){
            $row['niveau_dengagement'] = Constantes::REGULIER;
        }

        Log::info('row zone -- ' . $row['zone']);

        if(empty($zone)){
            Log::info('Row skipped in Excel file because the zone name ' . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->currentRow++;
            return null;
        }
 
        /*if(empty($sous_zone)){
            Log::info('Row skipped in Excel file because the souszone name ' . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->currentRow++;
            return null;
        }*/

        //If zone is not specified or does not exist, return null
        if($zone == null){
            Log::info('Row skipped in Excel file because the zone name ' . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->currentRow++;
            return null;
        }   

        //Create Sous zone if not exists
        if($sousZone == null){
            $sousZone = new SousZone();
            $sousZone->nom = $row['sous_zone'];
            $sousZone->quartier = $quartier;
            $sousZone->zone_id = $zone->id;
            $sousZone->has_country = $zone->nom == Constantes::ZONE_RESPONSABLE_GENERAL;
            $sousZone->save();
            $sousZone->refresh();   
        }

        //Create Groupe if not exists
        if($groupe == null){
            $groupe = new Groupe();
            $groupe->nom_groupe = $row['groupe'];
            $groupe->sous_zone_id = $sousZone->id;
            $groupe->jour_reunion = "RAS";
            $groupe->heure_reunion = '16:00';
            $groupe->save();
            $groupe->refresh();   
        }

        //Default apostolat is 'jeune'
        if($row['apostolat'] == "Celibataire" || $row['apostolat'] == "Célibataire"){
            $row['apostolat'] = Constantes::APOSTOLAT_JEUNES;
        }elseif($row['apostolat'] == "Fiancé"){
            $row['apostolat'] = Constantes::APOSTOLAT_JEUNES;
        }elseif($row['apostolat'] == "Mariée" || $row['apostolat'] == "Marié"){
            $row['apostolat'] = Constantes::APOSTOLAT_MARIES;
        }elseif($row['apostolat'] == "Single"){
            $row['apostolat'] = Constantes::APOSTOLAT_SINGLES;
        }else{
            $row['apostolat'] = Constantes::APOSTOLAT_JEUNES;
        }

        $apostolat = Apostolat::where('nom', $row['apostolat'])->first();
        $apostolat_id = $apostolat ? $apostolat->id : NULL;

        //email
        if(empty($row['email']) || $row['email'] == 'ras'){
            $email = 'ras'. Str::uuid().'@gmail.com';
        }else {
            $email = $row['email'];
        }

        //Categories
        if(empty($row['categorie'])){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }elseif($row['categorie'] == "Elève" || $row['categorie'] == "Eleve" ){
            $categorie = Constantes::CATEGORIE_SECONDAIRE_INTERMEDIAIRE;
        }elseif($row['categorie'] == "Etudiant" || $row['categorie'] == "Etudiante" || $row['categorie'] == "Etudiants"){
            $categorie = Constantes::CATEGORIE_UNIVERSITAIRE_DEBUTANT;
        }elseif($row['categorie'] == "Universitaire"){
            $categorie = Constantes::CATEGORIE_UNIVERSITAIRE_DEBUTANT;
        }elseif($row['categorie'] == "Femme Au Foyer") {
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }elseif($row['categorie'] == "Ing Qhse"){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }elseif($row['categorie'] == "Stagiaire"){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }elseif($row['categorie'] == "Travailleur"){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }elseif($row['categorie'] == "Travailleurs"){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }elseif($row['categorie'] == "Retraité"){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR_SENIOR;
        }else if (!in_array($row['categorie'], Constantes::CATEGORIE_SOCIALES)){
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        }

        $userExists = User::where(['nom' => $nom, 'prenom' => $prenoms,
                                'categorie_sociale' => $categorie,
                                'telephone1' => $row['telephone_whatsapp'] ])
                            ->orWhere('email', $email)->exists();

        if($userExists){
            Log::info('Row skipped in Excel file because user '.$nom.' '.$prenoms.' already exists in database.');
            $this->duplicatedRows[] = $nom.' '.$prenoms;
            $this->currentRow++;
            return null;
        }else if(empty($niveau_engagement_id)){
            Log::info('Row skipped in Excel file because the niveau d engagement '.$row['niveau_dengagement'].' for user '.$nom.' '.$prenoms.' is unknown.');
            $this->currentRow++;
            return null;
        }else if(empty($groupe_id)) {
            Log::info('Row skipped in Excel file because the group name ' . $row['groupe'] . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->currentRow++;
            return null;
        }else if(empty($apostolat_id)){
            Log::info('Row skipped in Excel file because the apostolat name '.$row['apostolat'].' for user '.$nom.' '.$prenoms.' is unknown.');
            $this->currentRow++;
            return null;
        }

        $user = User::create([
            'nom' => $nom,
            'prenom' => $prenoms,
            'adresse' => $row['ville'], //Put 'adresse' later
            'telephone1' => $row['telephone_whatsapp'],
            'telephone2' => "",
            'sexe' => $sexe,
            'email' => $email,
            'profession' => $row['profession_classe'],
            'specialite' => $row['specialite_filiere'],
            'quartier' => $quartier,
            'password' => \Illuminate\Support\Facades\Hash::make(time()),
            'niveau_engagement_id' => $niveau_engagement_id,
            'categorie_sociale' => $categorie,
            'groupe_id' => $groupe_id,
            'role' => Constantes::ROLE_MEMBRE,
            'etat' => Constantes::ETAT_ACTIF
        ]);

        //Store User Group
        $user->groupes()->attach($groupe_id, [
            'actif' => Constantes::ETAT_ACTIF
        ]);

        //Store User Apostolats
        DB::table('apostolat_user')->where(['user_id' => $user->id])->delete();
        //foreach($apostolat_id as $apostolat_id){
            $user->apostolats()->attach([
                'apostolat_id' => $apostolat_id
            ]);
        //}

        $this->importedCount++;
        $this->currentRow++;

        return $user;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getDuplicatedRows()
    {
        return $this->duplicatedRows;
    }
}
