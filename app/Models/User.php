<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Constantes;

class User extends AuthUser
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom', 'prenom', 'adresse', 'telephone1', 'telephone2', 'sexe', 'date_naissance', 'etat', 'email',
        'profession', 'pays', 'ville', 'quartier', 'niveau_engagement_id', 'role', 'categorie_sociale',
        'email_verified_at', 'password', 'date_entree'];

    /**================== Start Custom functions ===========================**/
    public function isActive(){
        return $this->etat == Constantes::ETAT_ACTIF;
    }

    public function isAdmin(){
        return $this->role == Constantes::ROLE_ADMIN;
    }
    /**================== End Custom functions ==============================**/

    /**================= Start Model Relation functions ===================**/
    public function niveauEngagement()
    {
        return $this->belongsTo(NiveauEngagement::class);
    }
    /*
    public function apostolat()
    {
        return $this->belongsTo(Apostolat::class);
    }
    */

    public function apostolats()
    {
        return $this->belongsToMany(Apostolat::class)->withTimestamps();
    }

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class)->withTimestamps()
            ->withPivot(['actif']);
    }

    public function activite()
    {
        return $this->belongsToMany(Activite::class)->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableGroupes()
    {
        return $this->belongsToMany(Groupe::class, 'responsable_groupe')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableSousZones()
    {
        return $this->belongsToMany(SousZone::class, 'responsable_sous_zone')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableZones()
    {
        return $this->belongsToMany(Zone::class, 'responsable_zone')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    /**============ End Model Relation functions ==============**/

}
