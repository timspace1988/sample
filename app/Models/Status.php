<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//this is a weibo model(weibo 動態)
class Status extends Model
{
    protected $fillable = ['content'];

    public function user(){
        return $this->belongsTo(User::class);
        //Eloquent model provides us a way to build relationship between models
        //above means a status belongs to one user
        //when executing $status->user(), it return the User record who post this status
    }
}
