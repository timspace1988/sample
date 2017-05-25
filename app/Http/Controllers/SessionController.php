<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class SessionController extends Controller
{
    public function __construct(){
        /*
        only unsigned visiter(guset) can access sign in page
        default redirected page for signed in user could be set in app/Http/Middleware/RedirectIfAuthenticate.php file
        */
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //return and display login page
    public function create(){
        return view('sessions.create');
    }

    //when we received  user's login input
    public function store(Request $request){
        //firstly, we need to validate user's login input
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        //if user's input has passed validation, then we will do user's authentication
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        //Auth's attempt method will automatically encrypt password input using bcrypt(), then compared it with record in database
        //laravel defaultly use users table to do authentication (users table automatically created by laravel )
        if(Auth::attempt($credentials, $request->has('remember'))){//the second parameter is a boolean type which decides if "remember me"
            //passed authentication, Auth will store uer authentication in its own session

            //when uer passed authentication, we need to check if user has activated his account,
            if(Auth::user()->activated){
                session()->flash('success', 'Welcome back!');//Note: this session has no relationship with authentication,

                //redirect to user's page
                //return redirect()->route('users.show', [Auth::user()]);//Auth::user() will get current login information
                return redirect()->intended(route('users.show', [Auth::user()]));
                /*
                note: redirect()->intended(default url) will redirect to our last request page(before we came to login page)
                e.g. unsigned in user visit edit profile page, user will be redirected to login page, after he signed in,
                user will be redirect back to edit profile page. if the last request is null, the default url will be used
                */
            }else{
                //if user was not activated, we let user log out, redirect user to home page and display a prompt
                Auth::logout();
                session()->flash('warning', 'Your account is not activated, please check the confirmation letter in your email inbox.');
                return redirect('/');
            }


        }else{
            //failed authentication
            session()->flash('danger', 'Sorry, incorrect email or password');
            return redirect()->back();
        }

    }

    //destroy user's session (log out)
    public function destroy(){
        Auth::logout();
        session()->flash('success', 'You have signed out.');
        return redirect('login');
    }
}
