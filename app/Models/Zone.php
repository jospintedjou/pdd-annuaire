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

    public function getMembres()
    {
        return User::select(['users.*','groupe_user.actif'])
            ->join('groupe_user', 'users.id', '=', 'groupe_user.user_id')
            ->join('groupes', 'groupe_user.groupe_id', '=', 'groupes.id')
            ->join('sous_zones', 'groupes.sous_zone_id', '=', 'sous_zones.id')
            ->join('zones', 'sous_zones.zone_id', '=', 'zones.id')
            ->where('groupe_user.actif', \App\Constantes::ETAT_ACTIF)
            ->where('zones.id', $this->id)
            ->get();
    }

    public function sousZones()
    {
        return $this->hasMany(SousZone::class);
    }

    public function responsableZones()
    {
        return $this->belongsToMany(User::class, 'responsable_zone')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }
}
