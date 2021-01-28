<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginJSController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function getToken(){
        if(Admin::user()){
           return auth()->tokenById(Admin::user()->id);
        } else {
            return 0;
        }

    }
}
