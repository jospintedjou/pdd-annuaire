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
        \Illuminate\Support\Facades\DB::table("niveau_engagements")->insert([
            'id' => 1,
            'nom' => Constantes::SIMPLE
        ]);
    }
}
