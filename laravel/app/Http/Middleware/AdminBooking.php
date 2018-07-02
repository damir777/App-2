<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminBooking
{
    public function handle($request, Closure $next)
    {
        //get user
        $user = Auth::user();

        if ($user->hasRole('User'))
        {
            return response()->json(['status' => 0, 'error' => trans('errors.user_role_error')]);
        }

        return $next($request);
    }
}
