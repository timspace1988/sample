<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //now we use factory function and  our defined factory model  to create 50 fake user
        $users = factory(User::class)->times(50)->make();//make() will create a Collection instance(集合) to contains these records
        User::insert($users->toArray());//insert these fake records into database

        //then we update the first record in database, so that we can use this user's data to log in
        // this is because we need to do migrate:refresh 重置 our database before we insert the fake records
        //(in some cases, we need this account to test administrator's function)
        $user = User::find(1);
        $user->name = 'Aufree';
        $user->email = 'aufree@estgroupe.com';
        $user->password = bcrypt('password');
        $user->is_admin = true;
        $user->save();
    }
}

/*
we need to tell DatabaseSeeder.php (created by laravel) to run this file
we doing this by $this->call('UsersTableSeeder'); in DatabaseSeeder.php
*/
