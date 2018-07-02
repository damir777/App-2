<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Booking;
use App\Customer;
use App\Repositories\BookingRepository;
use App\Repositories\UserRepository;
use App\Repositories\PaymentRepository;

class BookingController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new BookingRepository;
    }

    //get calendar
    public function getCalendar(Request $request)
    {
        $is_renter = $request->is_renter;
        $villa = $request->villa;
        $date = $request->date;
        $next_date = $request->next_date;
        $reset_date = $request->reset_date;

        //call getCalendar method from BookingRepository to get calendar
        $response = $this->repo->getCalendar($is_renter, $villa, $date, $next_date, $reset_date);

        return response()->json($response);
    }

    //get booking info
    public function getBookingInfo(Request $request)
    {
        $villa = $request->villa;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        //call getBookingInfo method from BookingRepository to get booking info
        $response = $this->repo->getBookingInfo($villa, $start_date, $end_date);

        return response()->json($response);
    }

    //insert booking
    public function insertBooking(Request $request)
    {
        $is_renter = $request->is_renter;
        $villa = $request->villa;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $renter_id = null;

        if ($is_renter == 'T')
        {
            //call getUserId method from UserRepository to get user id
            $this->repo = new UserRepository;
            $renter_id = $this->repo->getUserId();
        }

        //validate form inputs
        $validator = Validator::make($request->all(), Booking::validateBookingForm('F', $renter_id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //validate booking
        $validator = CustomValidator::booking('T', $villa, $start_date, $end_date);

        //if form input is not correct return error message
        if ($validator['status'] != 1)
        {
            return response()->json($validator);
        }

        //call insertBooking method from BookingRepository to insert booking
        $this->repo = new BookingRepository;
        $response = $this->repo->insertBooking('T', $villa, $start_date, $end_date);

        return response()->json($response);
    }

    //insert temp booking
    public function insertTempBooking(Request $request)
    {
        $villa = $request->villa;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $adults = $request->adults;
        $children = $request->children;
        $infants = $request->infants;

        //validate form inputs
        $validator = Validator::make($request->all(), Booking::validateBookingForm('T'));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //validate booking
        $validator = CustomValidator::booking('F', $villa, $start_date, $end_date, $adults, $children, $infants);

        //if form input is not correct return error message
        if ($validator['status'] != 1)
        {
            return response()->json($validator);
        }

        //call insertTempBooking method from BookingRepository to insert temp booking
        $response = $this->repo->insertTempBooking($villa, $start_date, $end_date, $adults, $children, $infants);

        //if response status != '1' show error message
        if ($response['status'] != 1)
        {
            return response()->json($response);
        }

        //call getTempBookingDetails method from BookingRepository to get temp booking details
        $response = $this->repo->getTempBookingDetails();

        return response()->json($response);
    }

    //insert booking customer
    public function insertBookingCustomer(Request $request)
    {
        $full_name = $request->full_name;
        $country = $request->country;
        $phone = $request->phone;
        $email = $request->email;

        //validate form inputs
        $validator = Validator::make($request->all(), Customer::validateCustomerForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //call insertBookingCustomer method from BookingRepository to insert booking customer
        $response = $this->repo->insertBookingCustomer($full_name, $country, $phone, $email);

        return response()->json($response);
    }

    //user confirm booking
    public function userConfirmBooking(Request $request)
    {
        $downpayment = $request->downpayment;
        $remaining_payment = $request->remaining_payment;

        //validate form inputs
        $validator = Validator::make($request->all(), Booking::$payment_type);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //call insertBookingPaymentType method from BookingRepository to insert booking payment type
        $response = $this->repo->insertBookingPaymentType($downpayment, $remaining_payment);

        //if response status != '1' return error message
        if ($response['status'] != 1)
        {
            return response()->json($response);
        }

        //if downpayment = '2' return success status
        if ($downpayment == 2)
        {
            return ['status' => 1, 'url' => route('PaymentPage')];
        }

        //call userConfirmBooking method from BookingRepository to confirm booking
        $response = $this->repo->userConfirmBooking();

        return response()->json($response);
    }

    //show payment page
    public function showPaymentPage()
    {
        //call getPaymentData method from PaymentRepository to get payment data
        $this->repo = new PaymentRepository;
        $payment = $this->repo->getPaymentData();

        //if response status = '0' show error page
        if ($payment['status'] == 0)
        {
            return view('errors.500');
        }

        return view('site.payment', ['data' => $payment['data'], 'url' => $payment['url']]);
    }

    //handle WSPay response
    public function handleWSPayResponse(Request $request)
    {
        //if WSPay success status = '1' confirm user booking, else redirect to booking page and show error message
        if ($request->Success)
        {
            //call userConfirmBooking method from BookingRepository to confirm booking
            $response = $this->repo->userConfirmBooking();

            //if response status = '0' return error message
            if ($response['status'] == 0)
            {
                return redirect(Session::get('booking_url'))->with('error_message', $response['error']);
            }
            //if response status = '2' return warning message
            elseif ($response['status'] == 2)
            {
                return redirect(Session::get('booking_url'))->with('warning_message', $response['warning']);
            }

            return redirect()->route('HomePage');
        }
        else
        {
            return redirect(Session::get('booking_url'))->with('error_message', $request->ErrorMessage);
        }
    }

    //confirm booking
    public function confirmBooking($id)
    {
        //call confirmBooking method from BookingRepository to confirm booking
        $response = $this->repo->confirmBooking($id);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('GetRenterNewBookings')->with('error_message', trans('errors.error'));
        }
        elseif ($response['status'] == 2)
        {
            return redirect()->route('GetRenterNewBookings')->with('warning_message', $response['warning']);
        }

        return redirect()->route('GetRenterBookings')->with('success_message', trans('main.booking_confirm'));
    }

    //reject booking
    public function rejectBooking(Request $request)
    {
        $id = $request->id;
        $message = $request->message;

        //call rejectBooking method from BookingRepository to reject booking
        $response = $this->repo->rejectBooking($id, $message);

        return response()->json($response);
    }
}
