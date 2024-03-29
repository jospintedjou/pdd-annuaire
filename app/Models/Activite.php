<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpParser\Node\Stmt\Goto_;

class Activite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['zone_id','sous_zone_id', 'groupe_id', 'type','date_debut', 'date_fin', 'heure_debut','lieu',
                'categorie_activite_id', 'created_at'];

    private $id_apostolats_concernes=NULL;

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function categorieActivite()
    {
        return $this->belongsTo(CategorieActivite::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function sousZone()
    {
        return $this->belongsTo(SousZone::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, Participation::class)
            ->withPivot(['user', 'heure_arrivee']);
    }

    public function apostolats(){
        return $this->hasManyThrough(Apostolat::class, ApostolatConcerne::class, NULL, "id");
    }

    public function id_apostolats(){
        if( $this->id_apostolats_concernes != NULL)
            goto exit_point;

        $this->id_apostolats_concernes = array();
        foreach($this->apostolats as $apostolat){
            array_push($this->id_apostolats_concernes, $apostolat->id);
        }

exit_point:
        return $this->id_apostolats_concernes;
    }
}
