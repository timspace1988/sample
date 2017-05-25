<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /*
      when user registered, we send them an activation toekn to their email.
      as this token will be stored in user record,  we need to create the token before the new user record was created
      In laravel, when we create an user record(instance), Eloquent model trigers an 'creating' event, this event happens before user instance was created
      So, we need to do listening on this event, and build the activation_token when this event happens
      PS: Eloqent model also has ohter events e.g. created
    */

    //
    public static function boot(){
        parent::boot();

        //Following is our listening on creating event, when it happends, we build the token and give it to the user record (to be created)
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });

        /*
          boot() will be executed after the User class was initialised, so we start our listening from here
        */
    }

    /*
    *Following method create user's profile portrait, google 'Gravatar' more details
    现在，我们要为用户的个人页面添加头像显示的功能。
    接下来的项目开发将使用 Gravatar 来为用户提供个人头像支持。Gravatar 为 “全球通用头像”，
    当你在 Gravatar 的服务器上放置了自己的头像后，
    可通过将自己的 Gravatar 登录邮箱进行 MD5 转码，
    并与 Gravatar 的 URL 进行拼接来获取到自己的 Gravatar 头像。
    */
    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

}
