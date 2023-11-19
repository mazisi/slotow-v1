<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class People extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    
    protected $guarded = [];

    protected $cascadeDeletes = ['licences','temporal_licences'];

    public function getDateOfBirthAttribute($value){

        return date("d-m-Y", strtotime($value));
    }

    public function nominations(){
        return $this->belongsToMany(Nomination::class)->withPivot('relationship','terminated_at','id');
    }

    public function company(){
       return $this->belongsToMany(Company::class)->withPivot('position');
    }

    public function people_documents()
    {
        return $this->hasMany(PeopleDocument::class);
    }

    public function temporal_licences()
    {
        return $this->hasMany(TemporalLicence::class);
    }

    public function licences()
    {
       return $this->hasMany(Licence::class);
    }

    public function licence_transfer(){
        return $this->hasMany(LicenceTransfer::class);
     }
 

}
