<?php

namespace Database\Seeders;

use App\Constantes;
use Illuminate\Database\Seeder;

class SousZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 1,
            'zone_id' => 5,
            'nom' => Constantes::SOUS_ZONE_AMERIQUE,
            'quartier' => 'RAS',
            'has_country' => 1,
        ]);
        
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 2,
            'zone_id' => 5,
            'nom' => Constantes::SOUS_ZONE_EUROPE_ASIE,
            'quartier' => 'RAS',
            'has_country' => 1,
        ]);

        // Insert the sous zones into the database
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 3,
            'zone_id' => 1,
            'nom' => Constantes::SOUS_ZONE_BIYEM_ASSI,
            'quartier' => Constantes::SOUS_ZONE_BIYEM_ASSI,
            'has_country' => 0,
        ]);
        
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 4,
            'zone_id' => 1,
            'nom' => Constantes::SOUS_ZONE_OMNISPORT,
            'quartier' => Constantes::SOUS_ZONE_OMNISPORT,
            'has_country' => 0,
        ]);
        
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 5,
            'zone_id' => 1,
            'nom' => Constantes::SOUS_ZONE_MVOLYE,
            'quartier' => Constantes::SOUS_ZONE_MVOLYE,
            'has_country' => 0,
        ]);
        
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 6,
            'zone_id' => 1,
            'nom' => Constantes::SOUS_ZONE_OLEMBE,
            'quartier' => Constantes::SOUS_ZONE_OLEMBE,
            'has_country' => 0,
        ]);
        
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 7,
            'zone_id' => 1,
            'nom' => Constantes::SOUS_ZONE_INSITA,
            'quartier' => 'RAS',
            'has_country' => 0,
        ]);
        
        \Illuminate\Support\Facades\DB::table("sous_zones")->insert([
            'id' => 8,
            'zone_id' => 1,
            'nom' => Constantes::SOUS_ZONE_GRAND_NORD,
            'quartier' => 'RAS',
            'has_country' => 0,
        ]);

    }
}
