<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nomination extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getDateOfBirthAttribute($value){

        return date("d-m-Y", strtotime($value));
    }
    
    public function people(){

    return $this->belongsToMany(
        People::class, 'nomination_people',  'nomination_id', 'people_id',);
}

    public function licence(){
        return $this->belongsTo(Licence::class);
    }

    public function merged_document()
    {
        return $this->hasOne(MergedDocument::class);
    }
}
