<?php

namespace Database\Seeders;

use App\Constantes;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert the zones into the database
        \Illuminate\Support\Facades\DB::table("zones")->insert([
            'id' => 1,
            'nom' => Constantes::ZONE_YAOUNDE,
            'continent' => Constantes::CONTINENT_AFRIQUE,
            'pays' => Constantes::PAYS_CAMEROUN,
            'ville' => Constantes::ZONE_YAOUNDE,
        ]);

        \Illuminate\Support\Facades\DB::table("zones")->insert([
            'id' => 2,
            'nom' => Constantes::ZONE_DOUALA,
            'continent' => Constantes::CONTINENT_AFRIQUE,
            'pays' => Constantes::PAYS_CAMEROUN,
            'ville' => Constantes::VILLE_DOUALA,
        ]);

        \Illuminate\Support\Facades\DB::table("zones")->insert([
            'id' => 3,
            'nom' => Constantes::ZONE_BAFOUSSAM,
            'continent' => Constantes::CONTINENT_AFRIQUE,
            'pays' => Constantes::PAYS_CAMEROUN,
            'ville' => Constantes::VILLE_BAFOUSSAM,
        ]);

        \Illuminate\Support\Facades\DB::table("zones")->insert([
            'id' => 4,
            'nom' => Constantes::ZONE_BAMENDA,
            'continent' => Constantes::CONTINENT_AFRIQUE,
            'pays' => Constantes::PAYS_CAMEROUN,
            'ville' => Constantes::VILLE_BAMENDA,
        ]);

        \Illuminate\Support\Facades\DB::table("zones")->insert([
            'id' => 5,
            'nom' => Constantes::ZONE_RESPONSABLE_GENERAL,
            'continent' => Constantes::CONTINENT_EUROPE,
            'pays' => Constantes::PAYS_FRANCE,
            'ville' => Constantes::VILLE_PARIS,
        ]);

    }
}
