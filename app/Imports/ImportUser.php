<?php

namespace App\Imports;

use App\Constantes;
use App\Models\Apostolat;
use App\Models\Groupe;
use App\Models\NiveauEngagement;
use App\Models\User;
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
            '*.noms' => ['required', 'exists:noms,nom'],
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
    public function model(array $row)
    {

        //Validate file header

        $niveau_engagement = NiveauEngagement::where('nom', $row['niveau_dengagement_2023'])->first();
        $niveau_engagement_id = $niveau_engagement ? $niveau_engagement->id : NULL;
        $groupe = Groupe::where('nom_groupe', $row['groupe'])->first();
        $groupe_id = $groupe ? $groupe->id : NULL;

        $sexe = $row['sexe'] == "Masculin" ? Constantes::SEXE_MASCULIN : Constantes::SEXE_FEMININ;
        $apostolat_id = 1;

       //dd($row['noms']);

        //Store User Group
        /*$user->groupes()->attach($request->input('groupe_id'), [
            'actif' => Constantes::ETAT_ACTIF
        ]);*/

        //Add specialite_filiere in user
        return new User([
            'nom' => $row['noms'],
            'prenom' => $row['prenoms'],
            'adresse' => "",
            'telephone1' => $row['telephone_whatsapp'],
            'telephone2' => "",
            'sexe' => $sexe,
            'email' => $row['email'],
            'profession' => $row['profession_classe'],
            'quartier' => "",
            'niveau_engagement_id' => $niveau_engagement_id,
            'categorie_sociale' => "",
            'groupe_id' => $groupe_id,
            'role' => Constantes::ROLE_MEMBRE,
            'date_entree' => NULL,
        ]);
    }

    public function headingRow(): int
    {
        return 2;
    }
}
