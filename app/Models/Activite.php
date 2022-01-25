<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['zone_id','sous_zone_id', 'groupe_id','date_debut', 'date_fin', 'heure_debut','lieu', 'categorie_activite_id'];
}
