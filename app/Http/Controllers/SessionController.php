<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class SessionController extends Controller
{
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
            //passed authentication
            session()->flash('success', 'Welcome back!');//Note: this session has no relationship with authentication,
            //Auth will store uer authentication in its own session
            //redirect to user's page
            return redirect()->route('users.show', [Auth::user()]);//Auth::user() will get current login information
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
