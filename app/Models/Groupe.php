<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupe extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['sous_zone_id','nom_groupe', 'paroisse', 'jour_reunion', 'heure_reunion'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['user_id', 'groupe_id', 'actif', 'date_entree']);
    }

    public function sousZone()
    {
        return $this->belongsTo(SousZone::class);
    }

    public function responsableGroupes()
    {
        return $this->belongsToMany(User::class, 'responsable_groupe')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }

}
