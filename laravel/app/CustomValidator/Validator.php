<?php

namespace App\CustomValidator;

use App\Villa;
use App\VillaPrice;
use App\Repositories\VillaRepository;
use App\Repositories\BookingRepository;

class Validator
{
    //validate booking
    public static function booking($is_admin, $villa, $start_date, $end_date, $adults = false, $children = false, $infants = false)
    {
        //get current date
        $current_date = date('Y-m-d');

        //format start and end date
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        $villa = Villa::where('id', '=', $villa)->first();

        /*
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        */

        $start_date_month = date('m', strtotime($start_date));
        $end_date_month = date('m', strtotime($end_date));

        $available_start_month = date('m', strtotime('2000-'.$villa->start_month.'-01'));
        $available_end_month = date('m', strtotime('2000-'.$villa->end_month.'-01'));

        //if villa is not available in these months return error message
        if ($start_date_month < $available_start_month || $start_date_month > $available_end_month ||
            $end_date_month < $available_start_month || $end_date_month > $available_end_month)
        {
            return ['status' => 0, 'error' => trans('errors.available_months_error')];
        }

        /*
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        */

        //get price start day
        $price_start = VillaPrice::select('start_day')->where('villa_id', '=', $villa->id)->orderBy('id', 'asc')->first();
        $price_start_day = date('m-d', strtotime($price_start->start_day));

        //get price end day
        $price_end = VillaPrice::select('end_day')->where('villa_id', '=', $villa->id)->orderBy('id', 'desc')->first();
        $price_end_day = date('m-d', strtotime($price_end->end_day));

        //set booking start month and day
        $booking_start_date = date('m-d', strtotime($start_date));

        //set booking end month and day
        $booking_end_date = date('m-d', strtotime($end_date));

        //if villa is not available in these days return error message
        if ($booking_start_date < $price_start_day || $booking_start_date > $price_end_day || $booking_end_date < $price_start_day ||
            $booking_end_date > $price_end_day)
        {
            return ['status' => 0, 'error' => trans('errors.available_months_error')];
        }

        /*
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        */

        //if this is user booking check max guests
        if ($is_admin == 'F')
        {
            //call setVillaFeaturedAttributes method from VillaRepository to set villa featured attributes
            $repo = new VillaRepository;
            $repo->setVillaFeaturedAttributes($villa);

            //set max guests
            $max_guests = $villa->featured_attributes[1]->value;

            if (($adults + $children) > $max_guests)
            {
                return ['status' => 0, 'error' => trans('errors.max_guests_error', ['guests' => $max_guests])];
            }
        }

        /*
        |--------------------------------------------------------------------------
        |--------------------------------------------------------------------------
        */

        //if start date is not greater than current date return warning message
        if ($start_date <= $current_date)
        {
            return ['status' => 2, 'warning' => trans('errors.booking_current_time_error')];
        }

        //if end date is not greater than start date return warning message
        if ($end_date <= $start_date)
        {
            return ['status' => 2, 'warning' => trans('errors.booking_end_time_error')];
        }

        //call checkBookingTime method from BookingRepository to check booking time
        $repo = new BookingRepository;
        $is_available = $repo->checkBookingTime('F', $villa->id, $start_date, $end_date);

        //if booking time is not available return error message
        if ($is_available == 'F')
        {
            return ['status' => 0, 'error' => trans('errors.booking_time_error')];
        }

        return ['status' => 1];
    }

    //validate villa prices
    public static function villaPrices($prices)
    {
        //set counter
        $counter = 1;

        //set default previous end date
        $previous_end_date = null;

        foreach ($prices as $price)
        {
            $current_start_date = self::formatDate($price['start_day']);
            $current_end_date = self::formatDate($price['end_day']);

            if ($counter != 1)
            {
                $compare_check = self::compareStartAndPreviousEndDate($current_start_date, $previous_end_date);

                //if current start date is not correct date return error message
                if (!$compare_check)
                {
                    return ['status' => 0, 'error' => trans('errors.villa_price_start_day')];
                }
            }

            //set previous end date
            $previous_end_date = $current_end_date;

            //increase counter
            $counter++;
        }

        return ['status' => 1];
    }

    private static function formatDate($date)
    {
        //set default year
        $year = '2000';

        return date('Y-m-d', strtotime($date.$year.'.'));
    }

    private static function compareStartAndPreviousEndDate($current_start_date, $previous_end_date)
    {
        //add one day to previous end date
        $previous_end_date = date('Y-m-d', strtotime('+1 day', strtotime($previous_end_date)));

        //if current start date is not day after previous end date return error status
        if ($current_start_date != $previous_end_date)
        {
            return 0;
        }

        return 1;
    }
}
