<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenceType extends Model
{
    use HasFactory;

    public function licence()
    {
        return $this->belongsTo(Licence::class,'licence_type');
    }
}
