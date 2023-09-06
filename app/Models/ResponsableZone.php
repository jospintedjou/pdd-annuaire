<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponsableZone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['responsabilite_id', 'actif', 'user_id', 'zone_id'];
}
