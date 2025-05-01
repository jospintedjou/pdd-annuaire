<?php

namespace Database\Seeders;

use App\Constantes;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert the pays into the database
        DB::table("pays")->insert([
            'id' => 1,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_FRANCE,
            'continent' => Constantes::CONTINENT_EUROPE
        ]);
        
        DB::table("pays")->insert([
            'id' => 2,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_ALLEMAGNE,
            'continent' => Constantes::CONTINENT_EUROPE
        ]);
        
        DB::table("pays")->insert([
            'id' => 3,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_BELGIQUE,
            'continent' => Constantes::CONTINENT_EUROPE
        ]);
        
        DB::table("pays")->insert([
            'id' => 4,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_ANGLETERRE,
            'continent' => Constantes::CONTINENT_EUROPE
        ]);
        
        DB::table("pays")->insert([
            'id' => 5,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_CHINE,
            'continent' => Constantes::CONTINENT_ASIE,
        ]);
        
        DB::table("pays")->insert([
            'id' => 6,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_USA,
            'continent' => Constantes::CONTINENT_AMERIQUE,
        ]);
        
        DB::table("pays")->insert([
            'id' => 7,
            'sous_zone_id' => 2,
            'nom' => Constantes::PAYS_CANADA,
            'continent' => Constantes::CONTINENT_AMERIQUE,
        ]);

    }
}
