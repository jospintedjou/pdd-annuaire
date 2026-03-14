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

    private $consecutiveEmpty = 0;

    private $done = false;

    private $skippedRows = [];

    private const MAX_CONSECUTIVE_EMPTY = 3;

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

        // Stop processing once we have seen enough consecutive fully-empty rows
        if ($this->done) {
            return null;
        }

        // Detect a completely empty row: all meaningful fields are blank
        // (only the pre-filled row-number column may have a value)
        $meaningfulFields = ['zone', 'sous_zone', 'groupe', 'noms', 'prenoms',
                             'sexe', 'apostolat', 'categorie', 'niveau_dengagement',
                             'profession_classe', 'specialite_filiere', 'ville',
                             'quartier', 'telephone_whatsapp', 'email'];
        $isCompletelyEmpty = true;
        foreach ($meaningfulFields as $field) {
            if (!empty($row[$field])) {
                $isCompletelyEmpty = false;
                break;
            }
        }

        if ($isCompletelyEmpty) {
            $this->consecutiveEmpty++;
            if ($this->consecutiveEmpty >= self::MAX_CONSECUTIVE_EMPTY) {
                $this->done = true;
                Log::info('Import stopped early at row ' . $this->currentRow . ' after ' . self::MAX_CONSECUTIVE_EMPTY . ' consecutive fully-empty rows.');
            }
            $this->currentRow++;
            return null;
        }

        // Row has data — reset the empty-row counter
        $this->consecutiveEmpty = 0;

        // Skip rows where zone, sous_zone or groupe is missing, but continue processing the rest
        if (empty($row['zone']) || empty($row['sous_zone']) || empty($row['groupe'])) {
            $missing = implode(', ', array_filter([
                empty($row['zone'])      ? 'zone'      : null,
                empty($row['sous_zone']) ? 'sous-zone'  : null,
                empty($row['groupe'])    ? 'groupe'     : null,
            ]));
            $nom_label = trim(($row['noms'] ?? '') . ' ' . ($row['prenoms'] ?? '')) ?: '—';
            Log::info('Row N° ' . $this->currentRow . ' skipped: missing ' . $missing . ' for ' . $nom_label);
            $this->skippedRows[] = [
                'row'    => $this->currentRow,
                'name'   => $nom_label,
                'reason' => 'Champ(s) obligatoire(s) manquant(s) : ' . $missing,
            ];
            $this->currentRow++;
            return null;
        }

        $row['zone'] = $row['zone'] == "ZONE DU RESPONSABLE GÉNÉRAL" 
                        ? Constantes::ZONE_RESPONSABLE_GENERAL : $row['zone'];

        $nom = empty($row['noms']) ? 'ras' : $row['noms'];
        $prenoms = empty($row['prenoms']) ? 'ras' : $row['prenoms'];
        $niveau_engagement = NiveauEngagement::whereRaw('LOWER(nom) = LOWER(?)', [trim($row['niveau_dengagement'] ?? '')])->first();
        $niveau_engagement_id = $niveau_engagement ? $niveau_engagement->id : NULL;
        $zone = Zone::where('nom', $row['zone'])->first();
        $sousZone = SousZone::where('nom', $row['sous_zone'])->first();
        $groupe = Groupe::where('nom_groupe', $row['groupe'])->first();
        $groupe_id = $groupe ? $groupe->id : NULL;
        $sexe = $row['sexe'] == "Masculin" || $row['sexe'] == "M" ? Constantes::SEXE_MASCULIN : Constantes::SEXE_FEMININ;
        $quartier = empty($row['quartier']) ? 'ras' : $row['quartier'];

        /*if(empty($groupe)) {
            Log::info('Row skipped in Excel file because the group name ' . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->currentRow++;
            return null;
        }*/

        //If not specified or not found, default to 'REGULIER' and re-fetch
        if(empty($niveau_engagement)){
            $row['niveau_dengagement'] = Constantes::REGULIER;
            $niveau_engagement = NiveauEngagement::whereRaw('LOWER(nom) = LOWER(?)', [Constantes::REGULIER])->first();
            $niveau_engagement_id = $niveau_engagement ? $niveau_engagement->id : NULL;
        }

        if(empty($zone)){
            Log::info('Row skipped in Excel file because the zone name ' . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->skippedRows[] = [
                'row'    => $this->currentRow,
                'name'   => $nom . ' ' . $prenoms,
                'reason' => 'Zone inconnue : ' . $row['zone'],
            ];
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
            $this->skippedRows[] = [
                'row'    => $this->currentRow,
                'name'   => $nom . ' ' . $prenoms,
                'reason' => 'Zone inconnue : ' . $row['zone'],
            ];
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

        //Categories — normalize raw value: uppercase + strip accents for reliable comparison
        $categorie_raw = trim($row['categorie'] ?? '');
        $categorie_norm = strtoupper(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $categorie_raw));

        // Known typos coming from Excel files (misspelling, not accent issues)
        $typoMap = [
            'SECONDAIRE INTERMEDIARE'  => Constantes::CATEGORIE_SECONDAIRE_INTERMEDIAIRE, // missing 'i'
        ];

        // Build a normalized → original map from all valid constants
        $normToConst = [];
        foreach (Constantes::CATEGORIE_SOCIALES as $const) {
            $normKey = strtoupper(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $const));
            $normToConst[$normKey] = $const;
        }

        if (empty($categorie_raw)) {
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        } elseif (isset($normToConst[$categorie_norm])) {
            // Normalized value matches a valid constant (handles accents like 'Adulte Marié' → 'ADULTE MARIE')
            $categorie = $normToConst[$categorie_norm];
        } elseif (isset($typoMap[$categorie_norm])) {
            $categorie = $typoMap[$categorie_norm];
        } elseif (in_array($categorie_norm, ['ELEVE'])) {
            $categorie = Constantes::CATEGORIE_SECONDAIRE_INTERMEDIAIRE;
        } elseif (in_array($categorie_norm, ['ETUDIANT', 'ETUDIANTE', 'ETUDIANTS', 'UNIVERSITAIRE', 'UNIVERSITAIRE DEBUTANT'])) {
            $categorie = Constantes::CATEGORIE_UNIVERSITAIRE_DEBUTANT;
        } elseif ($categorie_norm === 'UNIVERSITAIRE MAJEUR') {
            $categorie = Constantes::CATEGORIE_UNIVERSITAIRE_MAJEUR;
        } elseif ($categorie_norm === 'JEUNE TRAVAILLEUR MAJEUR') {
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR_MAJEUR;
        } elseif (in_array($categorie_norm, ['JEUNE TRAVAILLEUR', 'TRAVAILLEUR', 'TRAVAILLEURS', 'FEMME AU FOYER', 'ING QHSE', 'STAGIAIRE'])) {
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR;
        } elseif ($categorie_norm === 'RETRAITE') {
            $categorie = Constantes::CATEGORIE_JEUNE_TRAVAILLEUR_SENIOR;
        } else {
            Log::info('Unknown categorie value "' . $categorie_raw . '" for user ' . $nom . ' ' . $prenoms . ' — defaulting to JEUNE TRAVAILLEUR.');
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
            $this->skippedRows[] = [
                'row'    => $this->currentRow,
                'name'   => $nom . ' ' . $prenoms,
                'reason' => "Niveau d'engagement inconnu : " . $row['niveau_dengagement'],
            ];
            $this->currentRow++;
            return null;
        }else if(empty($groupe_id)) {
            Log::info('Row skipped in Excel file because the group name ' . $row['groupe'] . ' for user ' . $nom . ' ' . $prenoms . ' is unknown.');
            $this->skippedRows[] = [
                'row'    => $this->currentRow,
                'name'   => $nom . ' ' . $prenoms,
                'reason' => 'Groupe introuvable : ' . $row['groupe'],
            ];
            $this->currentRow++;
            return null;
        }else if(empty($apostolat_id)){
            Log::info('Row skipped in Excel file because the apostolat name '.$row['apostolat'].' for user '.$nom.' '.$prenoms.' is unknown.');
            $this->skippedRows[] = [
                'row'    => $this->currentRow,
                'name'   => $nom . ' ' . $prenoms,
                'reason' => 'Apostolat inconnu : ' . $row['apostolat'],
            ];
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

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}
