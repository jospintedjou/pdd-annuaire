<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SousZone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom', 'quartier', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }

    public function responsableSousZones()
    {
        return $this->belongsToMany(User::class, ResponsableSousZone::class)
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }

}
