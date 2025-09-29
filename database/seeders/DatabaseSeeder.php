<?php

namespace Database\Seeders;

use App\Models\Responsabilite;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        // \App\Models\User::factory(10)->create();
       
        $this->call(ApostolatSeeder::class);
        $this->call(NiveauEngagementSeeder::class);
        $this->call(ResponsabiliteSeeder::class);
        $this->call(CategorieActiviteSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AnneeSpirituelleSeeder::class);
        $this->call(ZoneSeeder::class);
        $this->call(SousZoneSeeder::class);
        $this->call(PaysSeeder::class);
    }
}
