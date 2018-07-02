<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class TempBooking extends Model
{
    public function villa()
    {
        return $this->belongsTo('App\Villa');
    }
}
