<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['activite_id', 'user', 'heure_arrive'];
}
