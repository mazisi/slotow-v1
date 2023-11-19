<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function people()
    {
        return $this->belongsTo(People::class);
    }
    
     public function getPathAttribute($value)
    {
        if(str_starts_with($value,"people-")|| str_starts_with($value,"mrnlabs/")){

            return $value;
        }

        return "docs/".$value;
    }
}
