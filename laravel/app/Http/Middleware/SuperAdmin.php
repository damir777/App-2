<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle($request, Closure $next)
    {
        //get user
        $user = Auth::user();

        if (!$user->hasRole('SuperAdmin'))
        {
            if ($request->ajax() || $request->wantsJson())
            {
                return response()->json(['status' => 0, 'error' => trans('errors.user_role_error')]);
            }

            return redirect()->route('HomePage')->with('error_message', trans('errors.user_role_error'));
        }

        return $next($request);
    }
}
