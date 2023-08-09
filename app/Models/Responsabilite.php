<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsabilite extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function responsableZones()
    {
        return $this->belongsToMany(User::class, 'responsable_zone')->withTimestamps()
                    ->withPivot(['nom_responsabilite','actif']);
    }


    public function responsableSousZones()
    {
        return $this->belongsToMany(User::class, 'responsable_sous_zone')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }

    public function responsableGroupes()
    {
        return $this->belongsToMany(User::class, 'responsable_groupe')->withTimestamps()
            ->withPivot(['nom_responsabilite', 'actif']);
    }
}
