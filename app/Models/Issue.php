<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUpdatedAtAttribute($value)
    {
        return date("d-m-Y H:i", strtotime($value));
    }
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
