<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __construct(){
        $this->middleware('auth', [
            'only' => ['store', 'destroy']
        ]);
        // only signed in user could post and delete his status
    }

    //action of post a status
    public function store(Request $request){
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        /*
          Auth::user() will return the currently signed in user,
          statuses()->create() will create Statuses record with pre-defined relationship (new Status record will contian user_id information)
        */
        Auth::user()->statuses()->create([
            'content' => $request->content
        ]);
        return redirect()->back();
    }

    //action of deleting a statuses
    public function destroy($id){
        $status = Status::findOrFail($id);
        $this->authorize('destroy', $status);//check if user has delete authority on this status
        $status->delete();
        session()->flash('success', 'Status has been deleted.');
        return redirect()->back();
    }

}
