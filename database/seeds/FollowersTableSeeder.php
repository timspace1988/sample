<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        //get all users except id with 1
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        //let fitst user follows all other users
        $user->follow($follower_ids);

        //All others follows first User
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
