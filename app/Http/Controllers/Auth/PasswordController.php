<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

/*
//
//new added by jia
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
//new added by jia
//
*/
class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    //laravel defalultly set '/home' as redirect link after successful password reset, however there is no action using /home in this project
    //So, we need to set this redirect link as '/'(home page for this project)
    protected $redirectPath = '/';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

/*
//
//new added by jia
    public function postEmail(Request $request)
    {
        echo "to start validationg<br>";
        $this->validate($request, ['email' => 'required|email']);
        echo "pass validationg<br>";
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            echo "in callback function<br>";
            $message->subject($this->getEmailSubject());
            echo "after callback function subject()<br>";
        });
        echo "$response<br>";
        echo "debug";
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

//new added
//
*/
}
