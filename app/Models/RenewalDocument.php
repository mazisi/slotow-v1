<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function licence_renewal()
    {
        return $this->belongsTo(LicenceRenewal::class);
    }
    
     public function getDocumentAttribute($value)
    {
        if(str_starts_with($value,"licence-")|| str_starts_with($value,"mrnlabs/")){

            return $value;
        }

        return "docs/".$value;
    }
}
