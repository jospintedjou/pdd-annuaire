<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constantes;

class NiveauEngagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $index = 1;
        // Insert the niveaux d'engagement into the database
        foreach (Constantes::NIVEAUX_ENGAGEMENT as $niveau) {
            \Illuminate\Support\Facades\DB::table("niveau_engagements")->insert([
                'id' => $index,
                'nom' => $niveau
            ]);

            $index++;
        }
       
    }
}
