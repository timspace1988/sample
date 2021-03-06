<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);//'UserTableSeeder::class' will return the class name
        $this->call('UsersTableSeeder');
        $this->call('StatusesTableSeeder');
        $this->call('FollowersTableSeeder');
        //note: be careful about the order of generating simulated data
        Model::reguard();
    }
}
