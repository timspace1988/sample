<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

/**
 * user policy is the our settings for user's authority
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //now we create an authorize function 'update', to perform authority check against our user authority policy
    //$currentUser is the current signed in user. It will be given by laravel when we call controller's authorize('update', $user) function.If this parameter is null, it will return false
    //$user is an User instance, operations on it need to be authorized(e.g. a user whose profile we are going to edit), this parameter will given by us

    /*
    Policy Mapping
    note: we also need to assign the user policy(class UserPolicy) to our user model(Class User) before we can ust authorize() fucntion in controller
    This will be done in app/Providers/AuthServiceProvider.php file
    */
    public function update(User $currentUser, User $user){
        //this is our authority policy
        //if the user(operations on it to be authorized) is the $currentUser, we will give $user the authority
        //the principle of above is actually the id comparation

        return $currentUser->id === $user->id;

    }

    //now we create destroy authority policy
    public function destroy(User $currentUser, User $user){
        return $currentUser->is_admin && $currentUser->id !== $user->id;
        //this policy only allow administrator to delete a user's record, and prevent administrator user from deleting themselves
    }
}
