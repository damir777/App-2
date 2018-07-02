<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Repositories\UserRepository;
use App\Repositories\GeneralRepository;

class AuthController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new UserRepository;
    }

    //login user
    public function loginUser(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $redirect_url = $request->redirect_url;

        //if login fails return error message
        if (!Auth::attempt(['email' => $email, 'password' => $password, 'active' => 'T']))
        {
            return response()->json(['status' => 0, 'error' => trans('errors.login_error')]);
        }

        //set default redirect variable
        $redirect = 'F';

        //if user doesn't have 'User' role set redirect variable to 'T' and redirect to login url
        if (!Auth::user()->hasRole('User'))
        {
            $redirect = 'T';

            //set locale session
            Session::put('locale', 'hr');

            //call getUserHomePageRoute method from GeneralRepository to get user home page route
            $repo = new GeneralRepository;
            $route = $repo->getUserHomePageRoute();

            $redirect_url = route($route);
        }

        return response()->json(['status' => 1, 'redirect' => $redirect, 'redirect_url' => $redirect_url]);
    }

    //register user
    public function registerUser(Request $request)
    {
        $full_name = $request->full_name;
        $email = $request->email;
        $password = $request->password;
        $country = $request->country;
        $phone = $request->phone;
        $redirect_url = $request->redirect_url;

        //validate form inputs
        $validator = Validator::make($request->all(), User::validateUserForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => $validator->errors()->all()[0]]);
        }

        //call insertUser method from UserRepository to insert user
        $response = $this->repo->insertUser($full_name, $email, $password, $country, $phone, null, null, $redirect_url);

        return response()->json($response);
    }

    //confirm user account
    public function confirmAccount(Request $request, $token)
    {
        //call confirmAccount method from UserRepository to confirm user account
        $this->repo = new UserRepository;
        $response = $this->repo->confirmAccount($token);

        //if response status = '0' return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('HomePage')->with('error_message', trans('errors.error'))->withInput();
        }

        return redirect($request->redirect_url)->with('success_message', trans('main.register_complete'));
    }

    //logout user
    public function logoutUser(Request $request)
    {
        if ($request->lang)
        {
            //call localeRedirect method to set locale and redirect
            return $this->localeRedirect($request->lang, 'LogoutUser');
        }

        //logout user
        Auth::logout();

        //get app locale
        $locale = Session::get('locale');

        //clear all session variables
        Session::flush();

        //set app locale
        App::setLocale($locale);

        //set locale session
        Session::put('locale', $locale);

        //redirect to login page
        return redirect()->route('HomePage');
    }
}
