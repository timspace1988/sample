<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;

use Auth;

use Mail;

class UsersController extends Controller
{
    public function __construct(){
        /*
        middleware(param1, param2), first parameter is name of middleware, second parameter is the filter operation,
        'auth' is an attribute of Auth middleware,
        'only' means only when user comes with 'auth' can pass the filtering for edit and update request. e.g. guest(who is not logged in), will be rejected and redirected
        'edit' and 'update' are action names

        Defaultly, laravel will redirect to /auth/login page if user is not logged in. however, in our project, we use /login as our sign in page,
        so, we need to modify it in app/Http/Middleware/Authenticate.php file
        */
        $this->middleware('auth', [
            'only' => ['edit', 'update', 'destroy']
        ]);


        /*
        only guest(unsigned visitor) could access sign up page
        'guest' is an attribute of Auth middleware
        default redirected page could be set in app/Http/Middleware/RedirectIfAuthenticate.php file
        */
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //request and display users list page (usually this is for administrator)
    public function index(){
        //$users = User::all();//get all records in users table (this is actually not good, we should use pagination later )
        $users = User::paginate(30);//use pagination and set each page having 30 records, on pages uder view, we need to render these record to get pagination links
        return view('users.index', compact('users'));
    }

    //request and display signup page
    public function create(){
        return view('users.create');//return resources/views/create.blade.php
    }

    //return and display user's personal page
    public function show($id){
        //the parameter must be id, cannot be user_id, because in rout$ers.php,defaultly use get('/users/{id}', 'UsersController@show'),
        //Above was tested with no affecting, so, just use user_id
        //'show' receives 'id'
        $user = User::findOrFail($id);
        //find all statuses posted by this user
        $statuses = $user->statuses()
                         ->orderBy('created_at', 'desc')
                         ->paginate(30);//30 statuses on each page

        return view('users.show', compact('user', 'statuses'));
        //compact will transform the user instance into an array(with data), and transit it to show page
        //We can directly use $user in pages under view forlder
    }

    //create and save user's profile data (sign up result)
    public function store(Request $request){
        //firstly, we need to validate user's input data
        //if not pass the validation, laravel will automatically redirect you to sign up page

        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        //when passed the validation, we create user record in databases
        // User::create() will create and store the new record in database, and return the instance $user of User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        /*

        //after we successfully create new user's record, we have the user signed in
        Auth::login($user);//this method will get user who passed authentication signed in
        //then  we display a successful sign up prompt
        session()->flash('success', 'Welcome to new world!');
        // flash() method will store data into a session, data includes a key and its values,
        // in this case, key is 'success', value is 'Welcoe~'
        // According to nees, in different cases, we might store succcess, danger, warning and info in session_destroy
        // So, we need to build html elements for these 4 differnt types in resources/views/shared/messages.blade.php


        //(after we create a new user record in database) then, we redirect to user's info page
        return redirect()->route('users.show', [$user]);

        */

        //when user registered, instead of leting user logged in and  redirecting to user's personal page,
        //we send an confirmaton email, keep user not logged in and display an email confirmaton prompt
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', 'A confirmaton letter has been sent to your email address, please check it and activate your account.');
        return redirect('/');//redirect to home page


    }

    //action of sending user a confirmation letter
    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@estgroupe.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = 'Thank you for your register, please confirm your emails address.';

        Mail::send($view, $data, function($message)use($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
        /*
          send() function has 3 parameters,
          first parameter is the name of view template for email
          second parameter is a data array which contains the user instance, which allows us to call $user in view page
          third parameter is a callback function, we can define the email sender, receiver, email's subject
          note: the instance $message is not given by us
        */
    }

    //action of users activating their account
    public function confirmEmail($token){
        //echo $token;
        $user = User::where('activation_token', $token)->firstOrFail();
        //echo "found";
        //where() was used to execute a select condition, it will return a 404 response if none was found

        $user->activated = true;//if we found user record(with the same token sent from user's email) in database, we set the user as activated
        $user->activation_token = "";//after user has been activated, we need to clear user's activation token
        $user->save();

        //Then we let the user logged in
        Auth::login($user);
        session()->flash('success', 'Congratulations, your account has been activated');
        return redirect()->route('users.show', [$user]);
    }

    //action of getting profile edit page
    public function edit($id){
        /*
        //check if user has beed signed in
        if(!Auth::check()){
            //if user is not signed in, we redirect user to login page
            return redirect('login');
        }

        as laravel has provided us Middleware to do this job, we will use it instead of above codes
        check the construct function at the top


        */

        //find the user record we are going to edit
        $user = User::findOrFail($id);

        //now we need to check if the currently logged in user has authority to perform edit operation on $user
        $this->authorize('update', $user);//'update' is the name of authorize policy(function)
        /*
        note: if the current user did not have the authority, it will throw a 403 HttpException to reject your request,
        otherwise, it will continue executing the following codes
        */

        return view('users.edit', compact('user'));//compact('user') allow us to call $user directly
    }

    //action of updating user's profile
    public function update($id, Request $request){
        //firtly we validate user's input
        $this->validate($request, [
            'name' => 'required|max:50',
            //'password' => 'required|confirmed|min:6',
            'password' => 'confirmed|min:6'//as the password could be empty, here 'mid:6' will be ignored when it is empty
        ]);

        //if passed the validation, we trying find the record in database
        $user = User::findOrFail($id);

        //now check the current logged in user's authority before update the record $user
        $this->authorize('update', $user);

        /*
        password could be empty, if user doesn't want to change it
        so, to avoid save empty vale into databse, we need to check if user want to change it
        */
        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        //then we update the record with new data sent by request and display an message for successfully updating user profile
        $user->update($data);
        session()->flash('success', 'Your profile is updated.');

        //Then we redirect to user's page
        return redirect()->route('users.show', $id);
    }

    //action of deleting a user record
    public function destroy($id){
        $user = User::findOrFail($id);

        //in order to prevent unsigned visitor send destroy request, we need add 'destroy' action to Auth middleware's auth attribute
        //also, only administrator has the authority to delete oter user record
        $this->authorize('destroy', $user);

        $user->delete();
        session()->flash('success', 'Selected user has been deleted.');
        return back();//redirec to last operation page (here should be users list page)

    }


}
