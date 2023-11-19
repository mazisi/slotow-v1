<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenceRenewal extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    

    

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }

    public function renewal_documents()
    {
        return $this->hasMany(RenewalDocument::class,'licence_renewal_id');
    }

    public function emails()
    {
        return $this->hasMany(Email::class,'model_id');
    }
}
