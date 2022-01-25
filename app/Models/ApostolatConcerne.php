<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApostolatConcerne extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['categorie_activite_id', 'apostolat_id'];
}
