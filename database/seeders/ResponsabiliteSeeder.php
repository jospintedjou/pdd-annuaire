<?php

namespace Database\Seeders;

use App\Constantes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsabiliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $responsabilites = Constantes::RESPONSABILITES;

        foreach($responsabilites as $responsabilite) {
            DB::table("responsabilites")->insert([
                'nom' => $responsabilite,
            ]);
        }
    }
}
