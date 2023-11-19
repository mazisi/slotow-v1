<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenceDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
    
    public function getDocumentFileAttribute($value)
    {
        if(str_starts_with($value,"licence-")|| str_starts_with($value,"mrnlabs/")){

            return $value;
        }

        return "docs/".$value;
    }
}
