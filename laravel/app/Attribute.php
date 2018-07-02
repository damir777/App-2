<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //form validation - insert/update attribute
    public static function validateAttributeForm($id = false)
    {
        $rules = [
            'category' => 'required|integer|exists:attribute_categories,id',
            'featured' => 'required|in:T,F',
            'searchable' => 'required|in:T,F',
            'is_input' => 'required|in:T,F',
            'icon' => 'nullable|mimes:jpg,jpeg,png,bmp,svg',
            'en_name' => 'required'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:attributes';
        }

        return $rules;
    }
}
