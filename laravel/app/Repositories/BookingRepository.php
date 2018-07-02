<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Notifications\confirmBooking;
use App\Notifications\rejectBooking;
use App\Notifications\newBooking;
use App\Notifications\newBookingForRenter;
use App\Booking;
use App\TempBooking;
use App\Villa;
use App\Customer;
use App\User;
use App\VillaPrice;

class BookingRepository extends UserRepository
{
    //get calendar
    public function getCalendar($is_renter, $villa_id, $date, $next_date, $reset_date = false)
    {
        try
        {
            $villa = Villa::where('id', '=', $villa_id);

            if ($is_renter == 'T')
            {
                $villa->where('renter_id', '=', $this->getUserId());
            }

            $villa = $villa->first();

            //if villa doesn't exist return warning message
            if (!$villa)
            {
                return ['status' => 2, 'warning' => trans('errors.invalid_villa')];
            }

            //set days array
            $days_array = [];

            //set months_array
            $months_array = [trans('main.january'), trans('main.february'), trans('main.march'), trans('main.april'),
                trans('main.may'), trans('main.june'), trans('main.july'), trans('main.august'), trans('main.september'),
                trans('main.october'), trans('main.november'), trans('main.december')];

            //if reset date is 'T' reset date to current date
            if ($reset_date == 'T')
            {
                $date = date('d.m.Y.');
            }

            //check next day
            if ($next_date != '0')
            {
                $date = date('d.m.Y.', strtotime($next_date.'1 months', strtotime($date)));
            }

            //get current month
            $current_month = date('n', strtotime($date));

            //call getVillaDisabledMonths method from VillaRepository to get villa disabled months
            $repo = new VillaRepository;
            $disabled_months_array = $repo->getVillaDisabledMonths($villa->start_month, $villa->end_month);

            //if current month is in disabled months array add one month to current
            while (in_array($current_month, $disabled_months_array))
            {
                if ($next_date == '0')
                {
                    $next_date = '+';
                }

                //add one month to current date
                $date = date('d.m.Y.', strtotime($next_date.'1 months', strtotime($date)));

                //set current month
                $current_month = date('n', strtotime($date));
            }

            //set calendar date
            $calendar_date = $months_array[date('m', strtotime($date)) - 1].' '.date('Y', strtotime($date)).'.';

            /*
            |--------------------------------------------------------------------------
            | Calendar
            |--------------------------------------------------------------------------
            */

            $month_days = date('t', strtotime($date));

            $month = date('n', strtotime($date));
            $year = date('Y', strtotime($date));

            $timestamp = mktime(0, 0, 0, $month, 1, $year);

            //get weekday of the first month day
            $first_day = date('N', $timestamp);

            $rows = 0;

            for ($i = 1; $i < $first_day; $i++)
            {
                //add day to days array
                $days_array[] = ['in_month' => 'F'];

                $rows++;
            }

            for ($day = 1; $day <= $month_days; $day++)
            {
                $booked = 'F';
                $enable_check_in = 'T';
                $enable_check_out = 'T';
                $booking_data_array = [];

                //set sql date
                $sql_date = date('Y-m-d', strtotime($year.'-'.$month.'-'.$day));

                //call checkBookingTime method to check is day is available
                $is_available = $this->checkBookingTime('T', $villa_id, $sql_date, $sql_date);

                //if current day is booked get booking info and set booked variable to 'T'
                if ($is_available == 'F')
                {
                    $booked = 'T';

                    $booking_data = Booking::with('user', 'customer')
                        ->select('user_id', 'customer_id', 'start_date as sql_start_date',
                            DB::raw('DATE_FORMAT(start_date, "%d.%m.%Y.") as start_date'),
                            DB::raw('DATE_FORMAT(end_date, "%d.%m.%Y.") as end_date'))
                        ->whereRaw('((? >= DATE(start_date)) AND (? <= DATE(end_date)))', [$sql_date, $sql_date])
                        ->where('villa_id', '=', $villa_id)->orderBy('sql_start_date', 'asc')->get();

                    foreach ($booking_data as $data)
                    {
                        $country = '';
                        $phone = '';
                        $email = '';

                        if ($data->user_id)
                        {
                            if ($data->user_id != 2)
                            {
                                $booking_user = $data->user->full_name;
                                $country = $data->user->userCountry->name;
                                $phone = $data->user->phone;
                                $email = $data->user->email;
                            }
                            else
                            {
                                $booking_user = trans('main.renter_booking');
                            }
                        }
                        else
                        {
                            $booking_user = $data->customer->full_name;
                            $country = $data->customer->customerCountry->name;
                            $phone = $data->customer->phone;
                            $email = $data->customer->email;
                        }

                        $booking_data_array[] = ['user' => $booking_user, 'country' => $country, 'phone' => $phone, 'email' => $email,
                            'date' => $data->start_date.' - '.$data->end_date];
                    }

                    //set previous previous sql date
                    $previous_sql_date = date('Y-m-d', strtotime('-1 days', strtotime($sql_date)));

                    //set next previous sql date
                    $next_sql_date = date('Y-m-d', strtotime('+1 days', strtotime($sql_date)));

                    //call checkBookingTime method to check is booking time
                    $is_previous_available = $this->checkBookingTime('T', $villa_id, $previous_sql_date, $previous_sql_date);

                    //if previous day is booked set enable check out to 'F'
                    if ($is_previous_available == 'F')
                    {
                        $enable_check_out = 'F';
                    }

                    //call checkBookingTime method to check is booking time
                    $is_next_available = $this->checkBookingTime('T', $villa_id, $next_sql_date, $next_sql_date);

                    //if next day is booked set enable check in to 'F'
                    if ($is_next_available == 'F')
                    {
                        $enable_check_in = 'F';
                    }
                }

                //add day to days array
                $days_array[] = ['in_month' => 'T', 'day' => $day, 'date' => date('d.m.Y.', strtotime($sql_date)),
                    'booked' => $booked, 'enable_check_in' => $enable_check_in, 'enable_check_out' => $enable_check_out,
                    'booking_data' => $booking_data_array];

                $rows++;
            }

            while ($rows % 7 != 0)
            {
                //add day to days array
                $days_array[] = ['in_month' => 'F'];

                $rows++;
            }

            return ['status' => 1, 'days' => $days_array, 'date' => $date, 'calendar_date' => $calendar_date];
        }
        catch (Exception $exp)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //check booking time
    public function checkBookingTime($is_calendar_check, $villa_id, $start_date, $end_date)
    {
        //set default is available variable
        $is_available = 'T';

        //format booking times
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        //count bookings with start and end date
        $bookings = Booking::where('villa_id', '=', $villa_id);

        if ($is_calendar_check == 'F')
        {
            //count bookings
            $count_bookings = Booking::where('villa_id', '=', $villa_id)->count();

            $bookings->whereRaw('(((? < start_date) AND (? <= start_date)) OR ((? >= end_date) AND (? > end_date)))',
                [$start_date, $end_date, $start_date, $end_date])->where('villa_id', '=', $villa_id);

            $bookings = $bookings->count();

            //if booking period is not available set available variable to 'F'
            if ($count_bookings != $bookings)
            {
                $is_available = 'F';
            }
        }
        else
        {
            $bookings->whereRaw('((? >= start_date) AND (? <= end_date))', [$start_date, $start_date]);
            $bookings = $bookings->count();

            //if booking period is not available set available variable to 'F'
            if ($bookings)
            {
                $is_available = 'F';
            }
        }

        return $is_available;
    }

    //insert booking
    public function insertBooking($is_admin, $villa_id, $start_date, $end_date)
    {
        try
        {
            //format start and end date
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            //start transaction
            DB::beginTransaction();

            //call checkBookingTime method to check booking time
            $is_available = $this->checkBookingTime('F', $villa_id, $start_date, $end_date);

            //if booking time is not available return error message
            if ($is_available == 'F')
            {
                return ['status' => 0, 'error' => trans('errors.booking_time_error')];
            }

            $booking = new Booking;
            $booking->villa_id = $villa_id;

            if ($is_admin == 'T')
            {
                $booking->user_id = 2;
                $booking->status_id = 3;
            }
            else
            {
                $booking->user_id = $this->getUserId();
            }

            $booking->start_date = $start_date;
            $booking->end_date = $end_date;
            $booking->downpayment_id = 1;
            $booking->remaining_payment_id = 1;
            $booking->save();

            //commit transaction
            DB::commit();

            return ['status' => 1, 'success' => trans('main.booking_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //set booking calendar
    public function setBookingCalendar($url_name)
    {
        try
        {
            //set no check in dates array
            $no_check_in_dates_array = [];

            //set no check out dates array
            $no_check_out_dates_array = [];

            //get villa
            $villa = Villa::select('id', 'start_month', 'end_month', 'season_start_month', 'season_end_month')
                ->where('url_name', '=', $url_name)->first();

            //call getVillaDisabledMonths method from VillaRepository to get villa disabled months
            $repo = new VillaRepository;
            $disabled_months_array = $repo->getVillaDisabledMonths($villa->start_month, $villa->end_month);

            //set season months array
            $season_months_array = [$villa->season_start_month, $villa->season_end_month];

            //set start month
            $start_month = date('n', strtotime('-1 months', strtotime(date('Y-m-d'))));

            //set end month
            $end_month = date('n', strtotime('+13 months', strtotime(date('Y-m-d'))));

            //set calendar end date
            $calendar_end_year = date('Y', strtotime('+12 months', strtotime(date('Y-m-d'))));
            $calendar_end_month = date('m', strtotime('+12 months', strtotime(date('Y-m-d'))));
            $calendar_end_day = date('t', strtotime($calendar_end_year.'-'.$calendar_end_month));
            $calendar_end_date = date('d.m.Y.', strtotime($calendar_end_year.'-'.$calendar_end_month.'-'.$calendar_end_day));

            //set start year and end year
            $start_year = date('Y');
            $end_year = $start_year + 1;

            //if start month = '12' start year is previous year
            if ($start_month == 12)
            {
                $start_year--;
            }

            //if start month = '11' increase end year
            if ($start_month == 11)
            {
                $end_year++;
            }

            //get bookings
            $bookings = Booking::where('villa_id', '=', $villa->id)->whereRaw('DATE_FORMAT(start_date, "%Y-%m") >= ? AND
                DATE_FORMAT(end_date, "%Y-%m") <= ?', [date('Y-m', strtotime($start_year.'-'.$start_month)),
                date('Y-m', strtotime($end_year.'-'.$end_month))])
                ->orderBy('start_date', 'asc')->get();

            foreach ($bookings as $booking)
            {
                $booking_start_date = $booking->start_date;
                $booking_end_date = $booking->end_date;
                $current_date = $booking_start_date;

                while ($current_date < $booking_end_date)
                {
                    //add date to no check in dates array
                    $no_check_in_dates_array[] = $current_date;

                    //add one day to current date and add it to no check out dates array
                    $no_check_out_dates_array[] = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));

                    //add one day to current date
                    $current_date = date('Y-m-d', strtotime('+1 days', strtotime($current_date)));
                }
            }

            //call getVillaDisabledDates method from VillaRepository to get villa disabled dates
            $repo = new VillaRepository;
            $disabled_dates_array = $repo->getVillaDisabledDays($villa->id, $villa->start_month, $villa->end_month);

            return ['status' => 1, 'no_check_in_dates' => $no_check_in_dates_array, 'no_check_out_dates' => $no_check_out_dates_array,
                'disabled_months' => $disabled_months_array, 'disabled_dates' => $disabled_dates_array,
                'season_months' => $season_months_array, 'calendar_end_date' => $calendar_end_date];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get booking info
    public function getBookingInfo($villa_id, $start_date, $end_date)
    {
        try
        {
            $villa = Villa::where('id', '=', $villa_id)->first();

            //if villa doesn't exist return warning message
            if (!$villa)
            {
                return ['status' => 2, 'warning' => trans('errors.invalid_villa')];
            }

            //call calculateBookingNights method to calculate booking nights
            $booking_nights = $this->calculateBookingNights($start_date, $end_date);

            //call calculateBookingSum method to calculate booking sum
            $sum = $this->calculateBookingSum($villa_id, $start_date, $end_date);

            return ['status' => 1, 'nights' => $booking_nights['formatted_nights'], 'sum' => $sum['formatted_sum']];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //calculate booking nights
    private function calculateBookingNights($start_date, $end_date)
    {
        $start_date = date_create(date('Y-m-d', strtotime($start_date)));
        $end_date = date_create(date('Y-m-d', strtotime($end_date)));

        //set booking nights
        $diff = date_diff($start_date, $end_date);
        $nights = $diff->format('%a');
        $formatted_nights = $nights.' '.trans_choice('main.nights', $diff->format('%a'));

        return ['nights' => $nights, 'formatted_nights' => $formatted_nights];
    }

    //calculate booking sum
    private function calculateBookingSum($villa_id, $start_date, $end_date)
    {
        //call getCurrencyRatio method from GeneralRepository to get currency ratio
        $repo = new GeneralRepository;
        $currency_ratio = $repo->getCurrencyRatio();

        //format start date and end date
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        //set sum
        $sum = 0;

        //loop through booking period
        while ($start_date < $end_date)
        {
            //call getVillaCurrentPrice method to get villa current price
            $price = $this->getVillaCurrentPrice($villa_id, $start_date);

            //set sum
            $sum += ($price / 7) * $currency_ratio['ratio'];

            //add one day to start date
            $start_date = date('Y-m-d', strtotime('+1 day', strtotime($start_date)));
        }

        $formatted_sum = number_format($sum, 0, '', '.').' '.$currency_ratio['sign'];

        return ['sum' => $sum, 'formatted_sum' => $formatted_sum];
    }

    //get villa current price
    private function getVillaCurrentPrice($villa_id, $booking_date = false)
    {
        //set default price date
        $price_date = date('Y-m-d', strtotime('2000-'.date('m-d')));

        //if booking date parameter exists set price date
        if ($booking_date)
        {
            $price_date = date('Y-m-d', strtotime('2000-'.date('m-d', strtotime($booking_date))));
        }

        //get villa price
        $villa_price = VillaPrice::with('villa')
            ->select('villa_id', 'price')->where('villa_id', '=', $villa_id)->where('start_day', '<=', $price_date)
            ->where('end_day', '>=', $price_date)->first();

        //set villa default price
        $price = $villa_price->price;

        //if villa has discount calculate new price
        if ($villa_price->villa->discount)
        {
            $price = $price - ($price / 100 * $villa_price->villa->discount);
        }

        return $price;
    }

    //set booking url session
    private function setBookingUrlSession()
    {
        //get temp booking villa id
        $booking = TempBooking::select('villa_id')->where('booking_token', '=', Session::get('booking'))->first();

        //get villa url name
        $villa_url_name = Villa::find($booking->villa_id)->url_name;

        //set temp booking url session
        Session::put('booking_url', route('GetBookingPage', $villa_url_name).'#payment');
    }

    //calculate booking payment
    private function calculateBookingPayment($villa_id, $start_date, $end_date)
    {
        //call getCurrencyRatio method from GeneralRepository to get currency ratio
        $repo = new GeneralRepository;
        $currency_ratio = $repo->getCurrencyRatio();

        //call calculateBookingSum method to calculate booking sum
        $booking_sum = $this->calculateBookingSum($villa_id, $start_date, $end_date);

        //set downpayment and remaining payment
        $downpayment = $booking_sum['sum'] / 10 * 3;
        $formatted_downpayment = number_format($downpayment, 0, '', '.').' '.$currency_ratio['sign'];
        $remaining_payment = $booking_sum['sum'] / 10 * 7;
        $formatted_remaining_payment = number_format($remaining_payment, 0, '', '.').' '.$currency_ratio['sign'];

        return ['downpayment' => $downpayment, 'formatted_downpayment' => $formatted_downpayment,
            'remaining_payment' => $remaining_payment, 'formatted_remaining_payment' => $formatted_remaining_payment];
    }

    //calculate due date
    private function calculateDueDate($start_date)
    {
        //format start date
        $start_date = date('Y-m-d', strtotime($start_date));

        //set due date
        $due_date = date('d.m.Y.', strtotime('-30 days', strtotime($start_date)));

        return $due_date;
    }

    //insert temp booking
    public function insertTempBooking($villa_id, $start_date, $end_date, $adults, $children, $infants)
    {
        try
        {
            //format start date and end date
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            //start transaction
            DB::beginTransaction();

            //if temp booking session doesn't exist generate temp booking token, else get temp booking token from temp booking session
            if (!Session::has('booking'))
            {
                //generate temp booking token
                $token = substr(md5(rand()), 0, 40);

                $booking = new TempBooking;
                $booking->booking_token = $token;
            }
            else
            {
                //get temp booking token from temp booking session
                $token = Session::get('booking');

                $booking = TempBooking::where('booking_token', '=', $token)->first();
            }

            $booking->villa_id = $villa_id;
            $booking->start_date = $start_date;
            $booking->end_date = $end_date;
            $booking->adults = $adults;
            $booking->children = $children;
            $booking->infants = $infants;
            $booking->downpayment_id = 1;
            $booking->remaining_payment_id = 1;
            $booking->save();

            //commit transaction
            DB::commit();

            //set temp booking session
            Session::put('booking', $token);

            //set booking url session
            $this->setBookingUrlSession();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get temp booking details
    public function getTempBookingDetails()
    {
        try
        {
            //set default booking id
            $booking_id = null;

            //set default start date
            $start_date = null;

            //set default end date
            $end_date = null;

            //set default adults, children and infants
            $adults = 0;
            $children = 0;
            $infants = 0;

            //set default customer data
            $full_name = null;
            $country = null;
            $phone = null;
            $email = null;

            //set default downpayment and remaining payment
            $downpayment = 0;
            $formatted_downpayment = 0;
            $remaining_payment = 0;
            $formatted_remaining_payment = 0;

            //set default due date
            $due_date = null;

            //if search dates session exists format start date and end date
            if (Session::has('search_dates'))
            {
                $search_dates = Session::get('search_dates');

                $start_date = date('d.m.Y.', strtotime(substr($search_dates, 0, 11)));
                $end_date = date('d.m.Y.', strtotime(substr($search_dates, 14, 25)));
            }

            //if temp booking session exists get temp booking data
            if (Session::has('booking'))
            {
                $booking = TempBooking::where('booking_token', '=', Session::get('booking'))->first();

                //if temp booking doesn't exist return error status
                if (!$booking)
                {
                    return ['status' => 0];
                }

                //set booking id
                $booking_id = $booking->id;

                $villa = Villa::where('id', '=', $booking->villa_id)->first();

                //set start date and end date
                $start_date = date('d.m.Y.', strtotime($booking->start_date));
                $end_date = date('d.m.Y.', strtotime($booking->end_date));

                //set adults, children and infants
                $adults = $booking->adults;
                $children = $booking->children;
                $infants = $booking->infants;

                //if temp booking customer exists set customer data
                if ($booking->customer_id)
                {
                    $customer = Customer::find($booking->customer_id);

                    $full_name = $customer->full_name;
                    $country = $customer->country;
                    $phone = $customer->phone;
                    $email = $customer->email;
                }

                //call calculateBookingPayment method to calculate booking downpayment and remaining payment
                $booking_payment = $this->calculateBookingPayment($villa->id, $start_date, $end_date);

                //set downpayment and remaining payment
                $downpayment = $booking_payment['downpayment'];
                $formatted_downpayment = $booking_payment['formatted_downpayment'];
                $remaining_payment = $booking_payment['remaining_payment'];
                $formatted_remaining_payment = $booking_payment['formatted_remaining_payment'];

                //call calculateDueDate method to calculate due date
                $due_date = $this->calculateDueDate($start_date);
            }

            return ['status' => 1, 'id' => $booking_id, 'start_date' => $start_date, 'end_date' => $end_date, 'adults' => $adults,
                'children' => $children, 'infants' => $infants, 'full_name' => $full_name, 'country' => $country, 'phone' => $phone,
                'email' => $email, 'downpayment' => $downpayment, 'formatted_downpayment' => $formatted_downpayment,
                'remaining_payment' => $remaining_payment, 'formatted_remaining_payment' => $formatted_remaining_payment,
                'due_date' => $due_date];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //insert booking customer
    public function insertBookingCustomer($full_name, $country, $phone, $email)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            $booking = TempBooking::where('booking_token', '=', Session::get('booking'))->first();

            //if temp booking doesn't exist return error status
            if (!$booking)
            {
                return ['status' => 0, 'error' => trans('errors.error')];
            }

            //if temp booking customer exists update customer, else insert new customer
            if ($booking->customer_id)
            {
                $customer = Customer::find($booking->customer_id);
                $customer->full_name = $full_name;
                $customer->country = $country;
                $customer->phone = $phone;
                $customer->email = $email;
                $customer->save();
            }
            else
            {
                $customer = new Customer;
                $customer->full_name = $full_name;
                $customer->country = $country;
                $customer->phone = $phone;
                $customer->email = $email;
                $customer->save();

                //add customer to temp booking
                $booking->customer_id = $customer->id;
                $booking->save();
            }

            //commit transaction
            DB::commit();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //insert booking payment type
    public function insertBookingPaymentType($downpayment, $remaining_payment)
    {
        try
        {
            //if temp booking session doesn't exist return error message
            if (!Session::has('booking'))
            {
                return ['status' => 0, 'error' => trans('errors.error')];
            }

            $booking = TempBooking::where('booking_token', '=', Session::get('booking'))->first();

            //if temp booking doesn't exist return error message
            if (!$booking)
            {
                return ['status' => 0, 'error' => trans('errors.error')];
            }

            //call checkBookingTime method to check booking time
            $is_available = $this->checkBookingTime('F', $booking->villa_id, $booking->start_date, $booking->end_date);

            //if booking time is not available return error message
            if ($is_available == 'F')
            {
                return ['status' => 2, 'warning' => trans('errors.booking_time_error')];
            }

            $booking->downpayment_id = $downpayment;
            $booking->remaining_payment_id = $remaining_payment;
            $booking->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //user confirm booking
    public function userConfirmBooking() /////////////////////////////////////
    {
        try
        {
            //if temp booking session doesn't exist return error message
            if (!Session::has('booking'))
            {
                return ['status' => 0, 'error' => trans('errors.error')];
            }

            $booking = TempBooking::where('booking_token', '=', Session::get('booking'))->first();

            //if temp booking doesn't exist return error message
            if (!$booking)
            {
                return ['status' => 0, 'error' => trans('errors.error')];
            }

            //call checkBookingTime method to check booking time
            $is_available = $this->checkBookingTime('F', $booking->villa_id, $booking->start_date, $booking->end_date);

            //if booking time is not available return error message
            if ($is_available == 'F')
            {
                return ['status' => 2, 'warning' => trans('errors.booking_time_error')];
            }

            //start transaction
            DB::beginTransaction();

            //insert new booking
            $booking_model = new Booking;
            $booking_model->villa_id = $booking->villa_id;

            //if user is logged in insert booking user, else insert booking customer
            if (Auth::user())
            {
                $booking_model->user_id = $this->getUserId();
            }
            else
            {
                $booking_model->customer_id = $booking->customer_id;
            }

            $booking_model->start_date = $booking->start_date;
            $booking_model->end_date = $booking->end_date;
            $booking_model->adults = $booking->adults;
            $booking_model->children = $booking->children;
            $booking_model->infants = $booking->infants;
            $booking_model->downpayment_id = $booking->downpayment_id;
            $booking_model->remaining_payment_id = $booking->remaining_payment_id;
            $booking_model->save();

            //set user email
            if ($booking_model->user_id)
            {
                $user_email = User::find($booking_model->user_id)->email;
            }
            else
            {
                $user_email = Customer::find($booking_model->customer_id)->email;
            }

            //create temp user and send mail to user
            (new User)->forceFill([
                'email' => $user_email
            ])->notify(new newBooking($booking_model));

            //get renter email
            $villa = Villa::with('renter')
                ->where('id', '=', $booking->villa_id)->first();

            //set confirm booking flash
            Session::flash('success_message', trans('main.booking_insert'));

            //set app locale for renter email
            App::setLocale('hr');

            //create temp user and send mail to renter
            (new User)->forceFill([
                //'email' => $villa->renter->email
                'email' => 'damir.kopic@betaware.hr'
            ])->notify(new newBookingForRenter($booking_model, $villa->renter));

            //delete temp booking
            $booking->delete();

            //commit transaction
            DB::commit();

            //clear temp booking session and booking url session
            Session::forget('booking');
            Session::forget('booking_url');

            return ['status' => 1, 'url' => route('HomePage')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get bookings
    public function getBookings($start_date, $end_date, $villa = false, $status = false)
    {
        try
        {
            $bookings = Booking::with('villa', 'user', 'customer', 'downpaymentType', 'status')
                ->select('id', 'villa_id', 'user_id', 'customer_id', 'start_date as sql_start_date',
                    DB::raw('DATE_FORMAT(start_date, "%d.%m.%Y.") AS start_date'),
                    DB::raw('DATE_FORMAT(end_date, "%d.%m.%Y.") AS end_date'), 'downpayment_id', 'status_id');

            $user = Auth::user();

            if ($user->hasRole('Renter'))
            {
                $bookings->whereHas('villa', function($query) {
                    $query->where('renter_id', '=', $this->getUserId());
                });

                if (!$status)
                {
                    $bookings->where('status_id', '!=', 1);
                }
            }

            if ($user->hasRole('User'))
            {
                $bookings->where('user_id', '=', $this->getUserId());
            }

            if ($status)
            {
                $bookings->where('status_id', '=', $status);
            }

            if ($villa)
            {
                $bookings->where('villa_id', '=', $villa);
            }

            if ($start_date)
            {
                $start_date = date('Y-m-d', strtotime($start_date));

                $bookings->where(function($query) use ($start_date) {
                    $query->where('start_date', '>=', $start_date)->orWhere('end_date', '>=', $start_date);
                });
            }

            if ($end_date)
            {
                $end_date = date('Y-m-d', strtotime($end_date));

                $bookings->where(function($query) use ($end_date) {
                    $query->where('start_date', '<=', $end_date)->orWhere('end_date', '<=', $end_date);
                });
            }

            $bookings = $bookings->orderBy('start_date', 'desc')->paginate(30);

            foreach ($bookings as $booking)
            {
                if ($booking->user_id)
                {
                    if ($booking->user_id != 2)
                    {
                        $booking_user = $booking->user->full_name.', '.$booking->user->userCountry->name.
                            '<br>'.$booking->user->phone.', '.$booking->user->email;
                    }
                    else
                    {
                        $booking_user = trans('main.renter_booking');
                    }
                }
                else
                {
                    $booking_user = $booking->customer->full_name.', '.$booking->customer->customerCountry->name.
                        '<br>'.$booking->customer->phone.', '.$booking->customer->email;
                }

                $booking->booking_user = $booking_user;
            }

            return ['status' => 1, 'data' => $bookings, 'current_date' => date('Y-m-d')];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //confirm booking
    public function confirmBooking($id)
    {
        try
        {
            $booking = Booking::with('villa', 'user', 'customer')
                ->whereHas('villa', function($query) {
                    $query->where('renter_id', '=', $this->getUserId());
                })
                ->where('id', '=', $id)->where('status_id', '=', 1)->where('user_id', '!=', 2)->first();

            //if booking doesn't exist return error status
            if (!$booking)
            {
                return ['status' => 0];
            }

            //set current date
            $current_date = date('Y-m-d');

            //if current date > booking start date return warning message
            if ($current_date > $booking->start_date)
            {
                return ['status' => 2, 'warning' => trans('errors.confirm_booking_error')];
            }

            //call checkBookingTime method to check booking time
            $is_available = $this->checkBookingTime('F', $booking->villa, $booking->start_date, $booking->end_date);

            //if booking time is not available return status
            if ($is_available == 'F')
            {
                return ['status' => 2, 'warning' => trans('errors.multiple_bookings_error')];
            }

            //start transaction
            DB::beginTransaction();

            //update booking status id to '3'
            $booking->status_id = 3;
            $booking->save();

            //set user email
            if ($booking->user_id)
            {
                $user_email = $booking->user->email;
            }
            else
            {
                $user_email = $booking->customer->email;
            }

            //create temp user and send mail to user
            (new User)->forceFill([
                'email' => $user_email
            ])->notify(new confirmBooking($booking));

            //commit transaction
            DB::commit();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //reject booking
    public function rejectBooking($id, $message)
    {
        try
        {
            $booking = Booking::with('villa', 'user', 'customer')
                ->whereHas('villa', function($query) {
                    $query->where('renter_id', '=', $this->getUserId());
                })
                ->where('id', '=', $id)->where('status_id', '=', 1)->where('user_id', '!=', 2)->first();

            //if booking doesn't exist return error message
            if (!$booking)
            {
                return ['status' => 0, 'error' => trans('errors.error')];
            }

            //set current date
            $current_date = date('Y-m-d');

            //if current date > booking start date return warning message
            if ($current_date > $booking->start_date)
            {
                return ['status' => 2, 'warning' => trans('errors.reject_booking_error')];
            }

            //start transaction
            DB::beginTransaction();

            //set user email
            if ($booking->user_id)
            {
                $user_email = $booking->user->email;
            }
            else
            {
                $user_email = $booking->customer->email;
            }

            //create temp user and send mail to user
            (new User)->forceFill([
                'email' => $user_email
            ])->notify(new rejectBooking($booking, $message));

            //delete booking
            $booking->delete();

            //commit transaction
            DB::commit();

            //set reject booking flash
            Session::flash('success_message', trans('main.booking_reject'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }
}
