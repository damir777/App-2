<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\confirmAccount;
use App\Role;
use App\User;
use App\InvoiceAddress;
use App\BankAccount;
use App\Host;

class UserRepository
{
    //get user id
    public function getUserId()
    {
        return Auth::user()->id;
    }

    /*
    |--------------------------------------------------------------------------
    | Renters
    |--------------------------------------------------------------------------
    */

    //get renters
    public function getRenters()
    {
        try
        {
            $renters = User::with('role')
                ->select('id', 'full_name', 'email', 'phone')
                ->whereHas('role', function($query) {
                    $query->where('role_id', '=', 2);
                })
                ->paginate(30);

            return ['status' => 1, 'data' => $renters];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert renter
    public function insertRenter($full_name, $email, $password, $address, $city, $zip_code, $phone, $oib, $invoice_full_name,
        $invoice_address, $invoice_city, $invoice_zip_code, $domestic_owner, $domestic_bank, $account_number, $foreign_owner, $foreign_bank,
        $iban, $swift, $host_full_name, $host_email, $languages)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            //get user role
            $role = Role::where('name', '=', 'Renter')->first();

            $renter = new User;
            $renter->full_name = $full_name;
            $renter->email = $email;
            $renter->password = Hash::make($password);
            $renter->address = $address;
            $renter->city = $city;
            $renter->zip_code = $zip_code;
            $renter->phone = $phone;
            $renter->oib = $oib;
            $renter->active = 'T';
            $renter->save();

            //attach user role to renter
            $renter->attachRole($role);

            //insert invoice address
            $address_model = new InvoiceAddress;
            $address_model->renter_id = $renter->id;
            $address_model->full_name = $invoice_full_name;
            $address_model->address = $invoice_address;
            $address_model->city = $invoice_city;
            $address_model->zip_code = $invoice_zip_code;
            $address_model->save();

            //insert bank account
            $account_model = new BankAccount;
            $account_model->renter_id = $renter->id;
            $account_model->domestic_owner = $domestic_owner;
            $account_model->domestic_bank = $domestic_bank;
            $account_model->account_number = $account_number;
            $account_model->foreign_owner = $foreign_owner;
            $account_model->foreign_bank = $foreign_bank;
            $account_model->iban = $iban;
            $account_model->swift = $swift;
            $account_model->save();

            //insert host
            $host_model = new Host;
            $host_model->renter_id = $renter->id;
            $host_model->full_name = $host_full_name;
            $host_model->email = $host_email;
            $host_model->languages = $languages;
            $host_model->save();

            //commit transaction
            DB::commit();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get renter details
    public function getRenterDetails($id)
    {
        try
        {
            $renter = User::with('role')
                ->where('id', '=', $id)->whereHas('role', function($query) {
                    $query->where('role_id', '=', 2);
                })->first();

            //if renter doesn't exist return error status
            if (!$renter)
            {
                return ['status' => 0];
            }

            //add invoice address to renter object
            $renter->invoice_address = InvoiceAddress::where('renter_id', '=', $id)->first();

            //add bank account to renter object
            $renter->bank_account = BankAccount::where('renter_id', '=', $id)->first();

            //add host to renter object
            $renter->host = Host::where('renter_id', '=', $id)->first();

            return ['status' => 1, 'data' => $renter];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update renter
    public function updateRenter($id, $full_name, $email, $password, $address, $city, $zip_code, $phone, $oib, $invoice_full_name,
        $invoice_address, $invoice_city, $invoice_zip_code, $domestic_owner, $domestic_bank, $account_number, $foreign_owner, $foreign_bank,
        $iban, $swift, $host_full_name, $host_email, $languages)
    {
        try
        {
            $renter = User::find($id);
            $renter->full_name = $full_name;
            $renter->email = $email;

            if ($password)
            {
                $renter->password = Hash::make($password);
            }

            $renter->address = $address;
            $renter->city = $city;
            $renter->zip_code = $zip_code;
            $renter->phone = $phone;
            $renter->oib = $oib;
            $renter->save();

            //update invoice address
            $address_model = InvoiceAddress::where('renter_id', '=', $id)->first();
            $address_model->full_name = $invoice_full_name;
            $address_model->address = $invoice_address;
            $address_model->city = $invoice_city;
            $address_model->zip_code = $invoice_zip_code;
            $address_model->save();

            //insert bank account
            $account_model = BankAccount::where('renter_id', '=', $id)->first();
            $account_model->domestic_owner = $domestic_owner;
            $account_model->domestic_bank = $domestic_bank;
            $account_model->account_number = $account_number;
            $account_model->foreign_owner = $foreign_owner;
            $account_model->foreign_bank = $foreign_bank;
            $account_model->iban = $iban;
            $account_model->swift = $swift;
            $account_model->save();

            //insert host
            $host_model = Host::where('renter_id', '=', $id)->first();
            $host_model->full_name = $host_full_name;
            $host_model->email = $host_email;
            $host_model->languages = $languages;
            $host_model->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //delete renter
    public function deleteRenter($id)
    {
        try
        {
            $renter = User::with('role')
                ->where('id', '=', $id)->whereHas('role', function($query) {
                    $query->where('role_id', '=', 2);
                })->first();

            //if renter doesn't exist return error status
            if (!$renter)
            {
                return ['status' => 0];
            }

            $renter->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get renters - select
    public function getRentersSelect($default_option = false)
    {
        try
        {
            //set renters array
            $renters_array = [];

            $renters = User::with('role')
                ->select('id', 'full_name')
                ->whereHas('role', function($query) {
                    $query->where('role_id', '=', 2);
                })
                ->get();

            if ($default_option)
            {
                //add default option to renters array
                $renters_array[0] = trans('main.choose_renter');
            }

            //loop through all renters
            foreach ($renters as $renter)
            {
                //add renter to renters array
                $renters_array[$renter->id] = $renter->full_name;
            }

            return ['status' => 1, 'data' => $renters_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    //get users
    public function getUsers()
    {
        try
        {
            $users = User::with('role')
                ->select('id', 'full_name', 'email', 'phone')
                ->whereHas('role', function($query) {
                    $query->where('role_id', '=', 3);
                })
                ->where('id', '!=', 2)
                ->paginate(30);

            return ['status' => 1, 'data' => $users];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert user
    public function insertUser($full_name, $email, $password, $country, $phone, $social = false, $social_id = false, $redirect_url = false)
    {
        try
        {
            //start transaction
            DB::beginTransaction();

            //generate token
            $token = substr(md5(rand()), 0, 40);

            //get user role
            $role = Role::where('name', '=', 'User')->first();

            $user = new User;
            $user->full_name = $full_name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->country = $country;
            $user->phone = $phone;

            if ($social)
            {
                if ($social == 'facebook')
                {
                    $user->facebook_id = $social_id;
                }
                else
                {
                    $user->google_id = $social_id;
                }

                $user->active = 'T';
            }
            else
            {
                $user->remember_token = $token;
            }

            $user->save();

            //attach user role to user
            $user->attachRole($role);

            if (!$social)
            {
                //send email to user
                $user->notify(new confirmAccount($token, $redirect_url));
            }

            //commit transaction
            DB::commit();

            return ['status' => 1, 'data' => $user, 'success' => trans('main.register_success')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get user details
    public function getUserDetails()
    {
        try
        {
            $user = User::with('role')
                ->where('id', '=', $this->getUserId())->where('id', '!=', 2)->whereHas('role', function($query) {
                    $query->where('role_id', '=', 3);
                })->first();

            //if user doesn't exist return error status
            if (!$user)
            {
                return ['status' => 0];
            }

            return ['status' => 1, 'data' => $user];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update user
    public function updateUser($id, $full_name, $email, $password, $country, $phone)
    {
        try
        {
            $user = User::find($id);
            $user->full_name = $full_name;
            $user->email = $email;

            if ($password)
            {
                $user->password = Hash::make($password);
            }

            $user->country = $country;
            $user->phone = $phone;
            $user->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //confirm user account
    public function confirmAccount($token)
    {
        try
        {
            $user = User::where('remember_token', '=', $token)->where('active', '=', 'F')->first();

            //if user doesn't exist return error status
            if (!$user)
            {
                return array('status' => 0);
            }

            //change user active status to 'T' and set remember token to 'NULL'
            $user->active = 'T';
            $user->remember_token = NULL;
            $user->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //check booking personal data
    public function checkBookingPersonalData()
    {
        try
        {
            $user = User::select('country', 'phone')->where('id', '=', $this->getUserId())->first();

            return ['status' => 1, 'country' => $user->country, 'phone' => $user->phone,
                'info' => trans('main.booking_personal_data_info')];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update booking personal data
    public function updateBookingPersonalData($country, $phone)
    {
        try
        {
            $user = User::find($this->getUserId());
            $user->country = $country;
            $user->phone = $phone;
            $user->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
