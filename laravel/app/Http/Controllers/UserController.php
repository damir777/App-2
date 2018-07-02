<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Repositories\UserRepository;
use App\Repositories\BookingRepository;

class UserController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct(Request $request)
    {
        //set repo
        $this->repo = new UserRepository;

        if (!$request->ajax())
        {
            $this->middleware(function ($request, $next) {

                //call shareCountriesList method to share countries list
                $this->shareCountriesList();

                //call translateBookingCalendar method to translate booking calendar
                $this->translateBookingCalendar();

                //call setBlogLinks method to set blog links
                $this->setBlogLinks();

                $user = Auth::user();

                //set username
                $this->username = $user->full_name;

                //share username with all views
                view()->share('username', $this->username);

                return $next($request);
            });
        }
    }

    //check booking personal data
    public function checkBookingPersonalData()
    {
        //call checkBookingPersonalData method from UserRepository to check booking personal data
        $response = $this->repo->checkBookingPersonalData();

        return response()->json($response);
    }

    //update booking personal data
    public function updateBookingPersonalData(Request $request)
    {
        $country = $request->country;
        $phone = $request->phone;

        //validate form inputs
        $validator = Validator::make($request->all(), User::$booking_personal_data);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //call updateBookingPersonalData method from UserRepository to update booking personal data
        $response = $this->repo->updateBookingPersonalData($country, $phone);

        return response()->json($response);
    }

    //get user profile
    public function getUserProfile(Request $request)
    {
        if ($request->lang)
        {
            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'GetUserProfile');
        }

        //call getUserDetails method from UserRepository to get user details
        $this->repo = new UserRepository;
        $user = $this->repo->getUserDetails();

        //if response status = '0' show error page
        if ($user['status'] == 0)
        {
            return view('errors.500');
        }

        //call setPageTitle method to set page title
        $this->setPageTitle('profile', 1);

        return view('user.profile', ['user' => $user['data']]);
    }

    //update user profile
    public function updateUserProfile(Request $request)
    {
        $full_name = $request->full_name;
        $email = $request->email;
        $password = $request->password;
        $country = $request->country;
        $phone = $request->phone;

        //call getUserId method from UserRepository to get user id
        $this->repo = new UserRepository;
        $id = $this->repo->getUserId();

        //validate form inputs
        $validator = Validator::make($request->all(), User::validateUserForm($id, $password));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return redirect()->route('GetUserProfile')->withErrors($validator)
                ->with('error_message', trans('errors.validation_error'))->withInput();
        }

        //call updateUser method from UserRepository to update user
        $response = $this->repo->updateUser($id, $full_name, $email, $password, $country, $phone);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('GetUserProfile')->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect()->route('GetUserProfile')->with('success_message', trans('main.user_profile_update'));
    }

    //get bookings
    public function getBookings(Request $request)
    {
        if ($request->lang)
        {
            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'GetUserBookings');
        }

        //get search parameters
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        //call getBookings method from BookingRepository to get bookings
        $this->repo = new BookingRepository;
        $bookings = $this->repo->getBookings($start_date, $end_date);

        //if response status = '0' show error page
        if ($bookings['status'] == 0)
        {
            return view('errors.500');
        }

        //call setPageTitle method to set page title
        $this->setPageTitle('bookings', 1);

        return view('user.bookings', ['start_date' => $start_date, 'end_date' => $end_date, 'bookings' => $bookings['data']]);
    }
}
