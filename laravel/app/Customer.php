<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;

    public function customerCountry()
    {
        return $this->belongsTo('App\Country', 'country', 'code');
    }

    //form validation - insert customer
    public static function validateCustomerForm()
    {
        $rules = [
            'full_name' => 'required',
            'country' => 'required|exists:countries,code',
            'phone' => 'required',
            'email' => 'required|email',
            'confirm_email' => 'required|email|same:email'
        ];

        return $rules;
    }
}
