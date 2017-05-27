<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
          here, followers table is actually a jointed table
          when we build a belongToMany relationship between two models(tables),
          laravel will defaultly use the table "table1_table2" as the jointed table,
          However, in our project, the joint table is "followers",
          so we need to tells laravel the name of our joint table by give a parameter to belongsToMany('model_name', 'joint_name')
          Pleas check laravel document Eloquent ORM relationship
        */
        Schema::create('followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('follower_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('followers');
    }
}
