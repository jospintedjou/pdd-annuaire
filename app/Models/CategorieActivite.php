<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorieActivite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom', 'periodicite', 'type_activite'];

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }

    public function apostolats()
    {
        return $this->belongsToMany(Apostolat::class, ApostolatConcerne::class);
    }

}
