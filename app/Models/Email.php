<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function licence_renewals()
    {
        return $this->belongsTo(LicenceRenewal::class,'model_id');
    }
}
