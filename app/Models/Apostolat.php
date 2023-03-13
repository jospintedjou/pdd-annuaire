<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apostolat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['nom'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function activites()
    {
        return $this->belongsToMany(Activite::class, ApostolatConcerne::class);
    }
}
