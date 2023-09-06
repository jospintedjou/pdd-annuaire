<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponsableGroupe extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['responsabilite_id', 'actif', 'user_id', 'groupe_id'];
}
