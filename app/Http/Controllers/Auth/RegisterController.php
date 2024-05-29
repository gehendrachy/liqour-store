<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class RegisterController extends Controller
{
    use HasRoles;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
        // dd(Auth::user());
        if(Auth::user()->status == '1') {

            if (Auth::user()->hasRole(['Vendor'])) {

                return '/vendor/'.Auth::user()->username.'/dashboard';

            } elseif (Auth::user()->hasRole(['Super Admin'])) {

                return '/admin';
            } else {

                return '/';
            }
        }else{
            Auth::logout();
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:6' , 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // dd('abcdef');
        $data['status'] = 1;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'wishlist' => '[]'
        ]);

        $user->assignRole($data['role']);

        return $user;
    }
}
