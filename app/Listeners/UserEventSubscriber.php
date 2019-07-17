<?php

namespace App\Listeners;

use App\User;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEventSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleUserLogin(Login $event)
    {
        $user = $event->user;
        $login = new UserLogin;
        $login->ip_address = Request::ip();
        $login->user_agent = Request::userAgent();
        $login->session_id = session()->getId();
        $login->login_time = Carbon::now();
        $user->user_logins()->save($login);
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleUserLogout(Logout $event)
    {
        $login = UserLogin::where('session_id', session()->getId())->first();
        if ($login) {
            $login->logout_time = Carbon::now();
            $login->save();
        }
    }

    public function subscribe($events)
    {
        /*$events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@handleUserLogin'
        );*/

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@handleUserLogout'
        );
    }
}
