<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    public function handle($request, Closure $next)
    {
        //if locale session exists set app locale
        if (Session::has('locale'))
        {
            //set app locale
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
