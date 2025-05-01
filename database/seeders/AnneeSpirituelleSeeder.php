<?php

namespace Database\Seeders;

use App\Constantes;
use Illuminate\Database\Seeder;

class AnneeSpirituelleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table("annee_spirituelles")->insert([
            'id' => 1,
            'nom' => "2024-2025",
            'date_debut' => "2024-08-22",
            'date_fin' => "2025-09-01",
            'etat' => Constantes::CONTINENT_EUROPE
        ]);
    }
}
