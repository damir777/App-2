<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeCategory extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    //form validation - insert/update category
    public static function validateCategoryForm($id = false)
    {
        $rules = [
            'en_name' => 'required'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:attribute_categories';
        }

        return $rules;
    }
}
