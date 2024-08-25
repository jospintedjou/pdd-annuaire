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
        return $this->belongsToMany(User::class, 'responsable_sous_zone')->withTimestamps()
            ->withPivot(['responsabilite_id', 'actif']);
    }

    public function getMembres()
    {
        return User::select(['users.*','groupe_user.actif'])
            ->join('groupe_user', 'users.id', '=', 'groupe_user.user_id')
            ->join('groupes', 'groupe_user.groupe_id', '=', 'groupes.id')
            ->join('sous_zones', 'groupes.sous_zone_id', '=', 'sous_zones.id')
            ->where('groupe_user.actif', \App\Constantes::ETAT_ACTIF)
            ->where('sous_zones.id', $this->id)
            ->orderby('groupes.nom_groupe', 'asc')
            ->orderby('users.nom', 'asc')
            ->get();
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }

}
