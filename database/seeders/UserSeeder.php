<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constantes;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \Illuminate\Support\Facades\DB::table("users")->insert([
            'id' => 1,
            'nom' => 'admin',
            'prenom' => 'admin',
            'adresse' => 'yaounde',
            'telephone1' => '6060606060',
            'sexe' => Constantes::SEXE_MASCULIN,
            'date_naissance' => date('Y-m-d H:i:s'),
            'etat' => Constantes::ETAT_ACTIF,
            'email' => 'admin@gmail.com',
            //'ville' => Constantes::VILLE_YAOUNDE,
            'niveau_engagement_id' => 1,
            'role' => Constantes::ROLE_ADMIN,
            'categorie_sociale' => Constantes::CATEGORIE_JEUNE_TRAVAILLEUR,
            'apostolat_id' => 1,
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
