<?php

namespace App\Imports;

use App\Constantes;
use App\Models\Apostolat;
use App\Models\Groupe;
use App\Models\NiveauEngagement;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ImportUser implements ToModel, WithHeadingRow
{

    /**
     * @param  int $headingRow
     */
    public function __construct()
    {
    }

    public function rules(): array
    {
        /*$headings_arr =  ["pays", "zone", "sous_zone", "groupe", "noms", "prenoms", "sexe",
            "statut_matrimonial", "categorie", "niveau_dengagement_2021", "niveau_dengagement_2022",
            "niveau_dengagement_2023", "profession_classe", "specialite_filiere", "ville",
            "telephone_whatsapp", "email"
        ];*/

        //$nameConcat = $this->noms.$this->prenoms;
        //dd($nameConcat);

       return [
            '*.zone' => ['required', 'exists:zones,nom'],
            '*.sous_zone' => ['required', 'exists:sous_zones,nom'],
            '*.groupe' => ['required', 'exists:groupes,nom'],
            '*.noms' => ['required', 'exists:users,nom'],
            '*.sexe' => ['required', 'in:'.Constantes::SEXE_MASCULIN.','.Constantes::SEXE_FEMININ.',SOCIAL'],
            '*.niveau_dengagement_2023' => ['required', 'exists:niveau_engagements,nom'],
            '*.profession_classe' => ['required', 'min:2'],
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

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    /*public function model(array $row)
    {*/
    public function model(array $row)
    {
        if(empty($row['pays']) || empty($row['zone']) || empty($row['sous_zone']) || empty($row['noms']) ||
            empty($row['groupe']) || empty($row['profession_classe'])) {
            return null;
        }

        //Validate file header

        $niveau_engagement = NiveauEngagement::where('nom', $row['niveau_dengagement_2023'])->first();
        $niveau_engagement_id = $niveau_engagement ? $niveau_engagement->id : NULL;
        $groupe = Groupe::where('nom_groupe', $row['groupe'])->first();
        $groupe_id = $groupe ? $groupe->id : NULL;

        $sexe = $row['sexe'] == "Masculin" ? Constantes::SEXE_MASCULIN : Constantes::SEXE_FEMININ;
        $apostolat = Apostolat::where('nom', $row['statut_matrimonial'])->first();
        $apostolat_id = $apostolat ? $apostolat->id : NULL;

        $categorie = $row['categorie'];

        //dd($apostolat_id);
        $userExists = User::where(['nom' => $row['noms'], 'prenom' => $row['prenoms'],
                                'categorie_sociale' => $row['categorie']])
                        ->orWhere(['telephone1' => $row['telephone_whatsapp']])
                        ->orWhere(['telephone2' => $row['telephone_whatsapp']])
                        ->orWhere('email', $row['email'])->exists();

        if($userExists || empty($niveau_engagement_id) || empty($groupe_id) || empty($apostolat_id)){
            return null;
        }

        $user = User::create([
            'nom' => $row['noms'],
            'prenom' => $row['prenoms'],
            'adresse' => $row['ville'], //Put 'adresse' later
            'telephone1' => $row['telephone_whatsapp'],
            'telephone2' => "",
            'sexe' => $sexe,
            'email' => $row['email'],
            'profession' => $row['profession_classe'],
            'quartier' => "",
            'password' => \Illuminate\Support\Facades\Hash::make("pass"),
            'niveau_engagement_id' => $niveau_engagement_id,
            'categorie_sociale' => $categorie,
            'groupe_id' => $groupe_id,
            'role' => Constantes::ROLE_MEMBRE,
            'etat' => Constantes::ETAT_ACTIF,
            'date_entree' => NULL,
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

        //Add specialite_filiere in user
        /*return new User([
            'nom' => $row['noms'],
            'prenom' => $row['prenoms'],
            'adresse' => $row['ville'], //Put 'adresse' later
            'telephone1' => $row['telephone_whatsapp'],
            'telephone2' => "",
            'sexe' => $sexe,
            'email' => $row['email'],
            'profession' => $row['profession_classe'],
            'quartier' => "",
            'password' => \Illuminate\Support\Facades\Hash::make("pass"),
            'niveau_engagement_id' => $niveau_engagement_id,
            'categorie_sociale' => $categorie,
            'groupe_id' => $groupe_id,
            'role' => Constantes::ROLE_MEMBRE,
            'etat' => Constantes::ETAT_ACTIF,
            'date_entree' => NULL,
        ]);*/

        return $user;
    }

    public function headingRow(): int
    {
        return 2;
    }
}
