<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportUser implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'nom' => $row[0],
            'prenom' => $row[1],
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
        ]);
    }
}
