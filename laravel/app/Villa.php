<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Villa extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function renter()
    {
        return $this->belongsTo('App\User', 'renter_id');
    }

    public function attribute()
    {
        return $this->hasMany('App\VillaAttribute');
    }

    //form validation - insert/update villa
    public static function validateVillaForm($id = false)
    {
        $rules = [
            'renter' => 'required|integer|exists:users,id',
            'name' => 'required',
            'url_name' => 'required|unique:villas,url_name',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'deposit' => 'required|integer',
            'pets_price' => 'required|integer',
            'en_short_description' => 'required|max:100',
            'en_description' => 'required',
            'cash_payment' => 'required|in:T,F',
            'featured' => 'required|in:T,F',
            'start_month' => 'required|integer|between:1,12',
            'end_month' => 'required|integer|between:1,12',
            'season_start_month' => 'required|integer|between:1,12',
            'season_end_month' => 'required|integer|between:1,12',
            'discount_type' => 'required|integer|in:0,1,2',
            'discount' => 'nullable|required_unless:discount_type,0|integer',
            'latitude' => 'required',
            'active' => 'required|in:T,F',
            'attributes.*.id' => 'required|integer|exists:attributes,id',
            'prices.*.start_day' => 'required|price_date',
            'prices.*.end_day' => 'required|price_date',
            'prices.*.price' => 'required|integer'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:villas';
            $rules['url_name'] = ['required', Rule::unique('villas')->ignore($id)];
        }
        else
        {
            $rules['url_name'] = 'required|unique:villas,url_name';
        }

        return $rules;
    }
}
