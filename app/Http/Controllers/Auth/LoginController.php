<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
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
    //protected $redirectTo = '/index';
    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    public function showLoginForm()
    {
        return view('architect.auth.login1');
    }

    public function redirectTo()
    {
        return route('index');
    }

    protected function authenticated(Request $request, $user)
    {
        $login = new UserLogin;
        $login->ip_address = $request->getClientIp();
        $login->user_agent = $request->userAgent();
        $login->session_id = session()->getId();
        $login->login_time = Carbon::now();
        $user->user_logins()->save($login);
    }
}
