<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Booking extends Model
{
    public function villa()
    {
        return $this->belongsTo('App\Villa', 'villa_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function downpaymentType()
    {
        return $this->belongsTo('App\PaymentType', 'downpayment_id');
    }

    public function remainingPaymentType()
    {
        return $this->belongsTo('App\PaymentType', 'remaining_payment_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Status', 'status_id');
    }

    //form validation - insert booking
    public static function validateBookingForm($is_user, $renter_id = false)
    {
        $rules = [
            'start_date' => 'required|custom_date',
            'end_date' => 'required|custom_date'
        ];

        if ($renter_id)
        {
            $rules['villa'] = ['required', 'integer', Rule::exists('villas', 'id')->where(function($query) use ($renter_id) {
                $query->where('renter_id', '=', $renter_id)->where('active', '=', 'T'); })];
        }
        else
        {
            $rules['villa'] = ['required', 'integer', Rule::exists('villas', 'id')->where(function($query) {
                $query->where('active', '=', 'T'); })];
        }

        if ($is_user == 'T')
        {
            $rules['adults'] = 'required|integer|min:1';
            $rules['children'] = 'required|integer';
            $rules['infants'] = 'required|integer';
        }

        return $rules;
    }

    public static $payment_type = [
        'downpayment' => 'required|integer|exists:payment_types,id',
        'remaining_payment' => 'required|integer|exists:payment_types,id'
    ];
}
