<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VillaPrice extends Model
{
    public $timestamps = false;

    public function villa()
    {
        return $this->belongsTo('App\Villa');
    }
}
