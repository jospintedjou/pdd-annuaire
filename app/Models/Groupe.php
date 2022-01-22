<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupe extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom_groupe', 'paroisse', 'jour_reunion', 'heure_reunion'];
}
