<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'continent', 'sous_zone_id'];

    public function sousZone()
    {
        return $this->belongsTo(SousZone::class);
    }

    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }

    public function responsablePays()
    {
        return $this->belongsToMany(User::class, 'responsable_pays')->withTimestamps()
            ->withPivot(['responsabilite_id', 'actif']);
    }

    public function getMembres()
    {
        return User::select(['users.*','groupe_user.actif'])
            ->join('groupe_user', 'users.id', '=', 'groupe_user.user_id')
            ->join('groupes', 'groupe_user.groupe_id', '=', 'groupes.id')
            ->join('pays', 'groupes.pays_id', '=', 'pays.id')
            ->where('groupe_user.actif', \App\Constantes::ETAT_ACTIF)
            ->where('pays.id', $this->id)
            ->orderby('groupes.nom_groupe', 'asc')
            ->orderby('users.nom', 'asc')
            ->get();
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }

}
