<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeSpirituelle extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = ['nom', 'date_debut', 'date_fin', 'etat'];
}
