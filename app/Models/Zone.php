<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom', 'continent', 'pays', 'ville'];

    public function sousZones()
    {
        return $this->hasMany(SousZone::class);
    }

    public function responsableZones()
    {
        return $this->belongsToMany(User::class, ResponsableZone::class)
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }
}
