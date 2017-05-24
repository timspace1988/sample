<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;

class UsersController extends Controller
{
    public function create(){
        return view('users.create');//return resources/views/create.blade.php
    }

    //return and display user's personal page
    public function show($id){
        //the parameter must be id, cannot be user_id, because in rout$ers.php,defaultly use get('/users/{id}', 'UsersController@show'),
        //Above was tested with no affecting, so, just use user_id
        //'show' receives 'id'
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
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
            'password' => 'required'
        ]);

        //when passed the validation, we create user record in databases
        // User::create() will create and store the new record in database, and return the instance $user of User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //after we successfully create new user's record, we display a successful sign up prompt
        session()->flash('success', 'Welcome to new world!');
        /*flash() method will store data into current session, data includes a key and its values,
        in this case, key is 'success', value is 'Welcoe~'
        According to nees, in different cases, we might store succcess, danger, warning and info in session_destroy
        So, we need to build html elements for these 4 differnt types in resources/views/shared/messages.blade.php */


        //(after we create a new user record in database) then, we redirect to user's info page
        return redirect()->route('users.show', [$user]);
    }
}
