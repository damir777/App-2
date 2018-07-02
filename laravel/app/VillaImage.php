<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VillaImage extends Model
{
    public $timestamps = false;

    //form validation - upload image
    public static function validateImageForm()
    {
        $rules = [
            'is_featured' => 'required|in:T,F',
            'is_insert' => 'required|in:T,F',
            'image' => 'required'
        ];

        return $rules;
    }
}
