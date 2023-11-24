<?php

namespace App\Imports;

use App\Constantes;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportUser implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        dd($row['noms']);
        return new User([
            'nom' => $row['noms'],
            'prenom' => $row['prenoms'],
            'adresse' => $row[2],
            'telephone1' => $row[3],
            'telephone2' => $row[4],
            'sexe' => $row[5],
            'email' => $row[6],
            'profession' => $row[7],
            'quartier' => $row[8],
            'niveau_engagement_id' => $row[9],
            'categorie_sociale' => $row[10],
            'apostolat_id' => $row[11],
            'groupe_id' => $row[12],
            'role' => Constantes::ROLE_MEMBRE,
            'date_entree' => Constantes::ROLE_MEMBRE,
        ]);
    }
    public function headingRow(): int
    {
        return 2;
    }
}
