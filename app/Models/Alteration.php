<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alteration extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function getCreatedAtAttribute($value){

        return date("d-m-Y", strtotime($value));
    }

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
}
