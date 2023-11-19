<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquorBoardRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }
}
