<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VillaAttribute extends Model
{
    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo('App\Attribute');
    }
}
