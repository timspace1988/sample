<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Auth;

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
    //this will allow us to do MassAssignment 批量賦值， otherwise, we can not assign value to these fields

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

    //build relationship between User and statuses
    public function statuses(){
        return $this->hasMany(Status::class);
        //relationship is: one user could have many statuses
        //when executing $user->statuses(), it will return all Status records which belongs to this user
    }

    //get all statuses of this user's followings and himself
    public function feed(){
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids, Auth::user()->id);
        return Status::whereIn('user_id', $user_ids)
                       ->with('user')//width() is 預加載， check laravel document Eloquent:關聯 預加載
                       ->orderBy('created_at', 'desc');

        /*
        //this only return current usrer's statuses
        return $this->statuses()
                    ->orderBy('created_at', 'desc');
        */
    }

    /*
      build relationship between users and follwers(also users), the "followers" table is a joint table
      Defaultly, laravel will search for followers_users table, but we use "followers" in this project,
      so we need to tell laravel
    */

    //return all fans of a user
    public function followers(){
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
        //param1: name of returned(searched) Model
        //param1: the joint(relationship) table we used in this project
        //param3: the foreign key in joint table which refers to current user
        //param3: the foreign key in joint table which refers to our acquired(searched) model instance

        //this actually returns an instance of BelongsToMany, you can get all folowers by calling $this->followers
    }

    //return all users current user has followed
    public function followings(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
        //this actually returns an instance of BelongsToMany, you can get all folowings by calling $this->followings
        echo "get followings";
    }

    //follow other users
    public function follow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');//compact will put a variable into an array
        }
        echo "before follow<br>";
        $this->followings()->sync($user_ids, false);
        //$this->followings() will return a user collection
        //sync()will add new ids to this user's followings's id and save new records in joint table, second parameter is "if removes other ids"
        //attach() doing similar job, but attach() allows same id be added multiple times. So we don't use it
    }

    //unfollow some users
    public function  unfollow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    //check if A following B
    public function isFollowing($user_id){
        echo "before check<br>";
        return $this->followings->contains($user_id);//$this->followings returns a collecton containing all his followings
    }

}
