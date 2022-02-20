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
        \Illuminate\Support\Facades\DB::table("apostolats")->insert([
            'id' => 1,
            'nom' => Constantes::APOSTOLAT_JEUNES
        ]);
    }
}
