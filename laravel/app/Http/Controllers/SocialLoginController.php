<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\Repositories\UserRepository;
use App\Repositories\GeneralRepository;

class SocialLoginController extends Controller
{
    //set repo variable
    private $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new UserRepository;
    }

    //redirect to social provider
    public function redirectToProvider(Request $request, $provider)
    {
        //format redirect url
        $redirect_url = $request->redirect_url;

        //if url hash exists append hash to url
        if ($request->hash)
        {
            $redirect_url .= '#'.$request->hash;
        }

        //set redirect url session
        Session::put('redirect_url', $redirect_url);

        return Socialite::driver($provider)->redirect();
    }

    //social provider callback
    public function providerCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        //call loginOrCreateUser method to login user
        $auth_user = $this->loginOrCreateUser($user, $provider);

        //if response status = 0 return error message
        if ($auth_user['status'] == 0)
        {
            return redirect()->route('HomePage')->with('error_message', trans('errors.error'));
        }

        auth()->login($auth_user['user'], true);

        //set redirect_url
        $redirect_url = Session::get('redirect_url');

        //clear redirect url session
        Session::forget('redirect_url');

        return redirect($redirect_url);
    }

    //login or create user
    private function loginOrCreateUser($provider_user, $provider)
    {
        try
        {
            //get user with provider id
            $authUser = User::where($provider.'_id', $provider_user->getId())->first();

            //if user exist return user
            if ($authUser)
            {
                return ['status' => 1, 'user' => $authUser];
            }

            //get user with provider email
            $authUser = User::where('email', $provider_user->getEmail())->first();

            //set provider column
            $provider_column = $provider.'_id';

            //if user with email exist update provider id
            if ($authUser)
            {
                $authUser->$provider_column = $provider_user->getId();
                $authUser->save();

                return ['status' => 1, 'user' => $authUser];
            }

            $full_name = $provider_user->getName();
            $email = $provider_user->getEmail();

            if (!$email)
            {
                $email_name = explode(' ', $full_name);
                $email = GeneralRepository::formatName($email_name[0]).'.'.GeneralRepository::formatName($email_name[1]).'@'.$provider.
                    '.com';
            }

            //call insertUser method from UserRepository to insert new user
            $this->repo = new UserRepository;
            $response = $this->repo->insertUser($full_name, $email, 'password', null, null, $provider, $provider_user->getId());

            //if response status = 0 return error status
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            return ['status' => 1, 'user' => $response['data']];
        }
        catch (Exception $exp)
        {
            return ['status' => 0];
        }
    }
}
