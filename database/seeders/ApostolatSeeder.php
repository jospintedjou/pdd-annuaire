<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constantes;

class ApostolatSeeder extends Seeder
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
        foreach (Constantes::APOSTOLATS as $nom) {
            \Illuminate\Support\Facades\DB::table("apostolats")->insert([
                'id' => $index,
                'nom' => $nom,
            ]);

            $index++;
        }
    }
}
