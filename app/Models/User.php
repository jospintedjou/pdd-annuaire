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
        'profession', 'pays', 'ville', 'quartier', 'niveau_engagement_id', 'role', 'categorie_sociale', 'apostolat_id',
        'email_verified_at', 'password'];

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

    public function apostolat()
    {
        return $this->belongsTo(Apostolat::class);
    }

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, GroupeUser::class)
            ->withPivot(['user_id', 'groupe_id', 'actif', 'created_at']);
    }

    public function activite()
    {
        return $this->belongsToMany(Activite::class, Participation::class)
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableGroupes()
    {
        return $this->belongsToMany(User::class, ResponsableGroupe::class)
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableSousZones()
    {
        return $this->belongsToMany(User::class, ResponsableSousZone::class)
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableZones()
    {
        return $this->belongsToMany(User::class, ResponsableZone::class)
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    /**============ End Model Relation functions ==============**/

}
