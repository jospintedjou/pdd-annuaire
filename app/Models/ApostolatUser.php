<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApostolatUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'apostolat_id'];

}
