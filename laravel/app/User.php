<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use Notifiable, LaratrustUserTrait, SoftDeletes;

    protected $dates = ['deleted_at'];

    public function role()
    {
        return $this->hasOne('App\RoleUser', 'user_id', 'id');
    }

    public function userCountry()
    {
        return $this->belongsTo('App\Country', 'country', 'code');
    }

    //form validation - insert/update renter
    public static function validateRenterForm($id = false, $password = false)
    {
        $rules = [
            'full_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'oib' => 'required|oib',
            'invoice_full_name' => 'required',
            'invoice_address' => 'required',
            'invoice_city' => 'required',
            'invoice_zip_code' => 'required',
            'domestic_owner' => 'required',
            'domestic_bank' => 'required',
            'account_number' => 'required',
            'foreign_owner' => 'required',
            'foreign_bank' => 'required',
            'iban' => 'required',
            'swift' => 'required',
            'host_full_name' => 'required',
            'host_email' => 'required|email',
            'host_languages' => 'required'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:users';
            $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($id)];

            if ($password)
            {
                $rules['password'] = 'required|min:6|confirmed';
                $rules['password_confirmation'] = 'required|min:6';
            }
        }
        else
        {
            $rules['email'] = 'required|email|unique:users';
            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';
        }

        return $rules;
    }

    //form validation - insert/update user
    public static function validateUserForm($id = false, $password = false)
    {
        $rules = [
            'full_name' => 'required',
            'country' => 'required|exists:countries,code',
            'phone' => 'required'
        ];

        if ($id)
        {
            $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($id)];

            if ($password)
            {
                $rules['password'] = 'required|min:6|confirmed';
                $rules['password_confirmation'] = 'required|min:6';
            }
        }
        else
        {
            $rules['email'] = 'required|email|unique:users';
            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';
            $rules['captcha'] = 'required|captcha';
        }

        return $rules;
    }

    public static $booking_personal_data = [
        'country' => 'required|exists:countries,code',
        'phone' => 'required'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
