<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class RedirectIfAuthenticated
{
    use HasRoles;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->status == '1') {

                if (Auth::user()->hasRole(['Vendor'])) {

                    return redirect('/vendor/'.Auth::user()->username.'/dashboard')->with('error','Already Logged In as a Vendor!');

                } elseif (Auth::user()->hasRole(['Super Admin'])) {

                    return redirect('/admin')->with('error','Already Logged In!');
                } else {

                    return redirect('/user/p/account')->with('error','You are Already Logged In as Customer!');
                }
            }else{
                Auth::logout();
            }
        }

        return $next($request);
    }
}
