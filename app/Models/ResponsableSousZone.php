<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponsableSousZone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom_responsabilite', 'actif', 'user_id', 'sous_zone_id'];
}
