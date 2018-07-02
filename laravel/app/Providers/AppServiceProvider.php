<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    //set menu variable
    private $menu = 'menu.user';

    //set username variable
    private $username;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Share menu view and username with all views
        |--------------------------------------------------------------------------
        */

        $this->app['events']->listen(Authenticated::class, function ($e) {

            if ($e->user->hasRole('SuperAdmin'))
            {
                $this->menu = 'menu.superAdmin';
            }
            elseif ($e->user->hasRole('Renter'))
            {
                $this->menu = 'menu.renter';
            }

            view()->share('menu', $this->menu);

            //set username
            $this->username = $e->user->first_name.' '.$e->user->last_name;

            //share username with all views
            view()->share('username', $this->username);
        });

        /*
        |--------------------------------------------------------------------------
        | Additional validation rules
        |--------------------------------------------------------------------------
        */

        Validator::extend('custom_date', function($attribute, $value, $parameters, $validator)
        {
            return preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\.$/', $value);
        });

        Validator::extend('decimal', function($attribute, $value, $parameters, $validator)
        {
            return preg_match('/^[0-9]+(\.[0-9]+)?$/', $value);
        });

        Validator::extend('oib', function($attribute, $value, $parameters, $validator)
        {
            return preg_match('/^[0-9]{11}$/', $value);
        });

        Validator::extend('price_date', function($attribute, $value, $parameters, $validator)
        {
            return preg_match('/^[0-9]{2}\.[0-9]{2}\.$/', $value);
        });

        /*
        |--------------------------------------------------------------------------
        | Set default string length for SQL indexes
        |--------------------------------------------------------------------------
        */

        Schema::defaultStringLength(191);

        /*
        |--------------------------------------------------------------------------
        | Set default currency session
        |--------------------------------------------------------------------------
        */

        //if currency session doesn't exist set currency to 'EUR'
        if (!Session::has('currency'))
        {
            Session::put('currency', 'EUR');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
