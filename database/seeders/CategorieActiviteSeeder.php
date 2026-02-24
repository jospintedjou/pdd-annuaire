<?php

namespace Database\Seeders;

use App\Constantes;
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
                'periodicite' => Constantes::PERIODE_MENSUELLE,
                'type_activite' => Constantes::ACTIVITE_ZONALE
            ],
            [
                'nom' => 'Retraite mensuelle',
                'periodicite' => Constantes::PERIODE_MENSUELLE,
                'type_activite' => Constantes::ACTIVITE_ZONALE
            ],
            [
                'nom' => 'Reunion hebdomadaire',
                'periodicite' => Constantes::PERIODE_HEBDOMADAIRE,
                'type_activite' => Constantes::ACTIVITE_GROUPE
            ],
            [
                'nom' => 'Soirée des mariés',
                'periodicite' => Constantes::PERIODE_HEBDOMADAIRE,
                'type_activite' => Constantes::ACTIVITE_ZONALE
            ],
            [
                'nom' => 'Weekend des mariés et personnes singles',
                'periodicite' => Constantes::PERIODE_ANNUELLE,
                'type_activite' => Constantes::ACTIVITE_REGIONALE
            ],
            [
                'nom' => 'Retraite de carême',
                'periodicite' => Constantes::PERIODE_ANNUELLE,
                'type_activite' => Constantes::ACTIVITE_REGIONALE
            ],
            [
                'nom' => 'Grande retraite',
                'periodicite' => Constantes::PERIODE_ANNUELLE,
                'type_activite' => Constantes::ACTIVITE_REGIONALE
            ],
            [
                'nom' => 'Weekend de retraite des membres actifs',
                'periodicite' => Constantes::PERIODE_ANNUELLE,
                'type_activite' => Constantes::ACTIVITE_REGIONALE
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