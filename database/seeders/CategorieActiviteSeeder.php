<?php

namespace Database\Seeders;

use App\Models\CategorieActivite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieActiviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'nom' => 'Formation des jeunes',
                'periodicite' => 'mensuelle',
                'type_activite' => 'zonale'
            ],
            [
                'nom' => 'Retraite mensuelle',
                'periodicite' => 'mensuelle',
                'type_activite' => 'zonale'
            ],
            [
                'nom' => 'Reunion hebdomadaire',
                'periodicite' => 'hebdomadaire',
                'type_activite' => 'groupe'
            ],
            [
                'nom' => 'Soirée des mariés',
                'periodicite' => 'hebdomadaire',
                'type_activite' => 'zonale'
            ],
            [
                'nom' => 'Weekend des mariés et personnes singles',
                'periodicite' => 'annuel',
                'type_activite' => 'regionale'
            ],
            [
                'nom' => 'Retraite de carême',
                'periodicite' => 'annuel',
                'type_activite' => 'regionale'
            ],
            [
                'nom' => 'Grande retraite',
                'periodicite' => 'annuel',
                'type_activite' => 'regionale'
            ],
            [
                'nom' => 'Weekend de retraite des membres actifs',
                'periodicite' => 'annuel',
                'type_activite' => 'regionale'
            ]
        ];

        foreach ($categories as $category) {
            CategorieActivite::create([
                'nom' => $category['nom'],
                'periodicite' => $category['periodicite'],
                'type_activite' => $category['type_activite']
            ]);
        }
    }
}