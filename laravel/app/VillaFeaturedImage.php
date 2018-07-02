<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VillaFeaturedImage extends Model
{
    public $timestamps = false;

    public function villa()
    {
        return $this->belongsTo('App\Villa');
    }
}
