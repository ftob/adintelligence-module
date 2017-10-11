<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTaskTable
 */
class CreateTaskTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function(Blueprint $t)
        {
            $t->increments('id')->unsigned();
            $t->string('url', 512)->unique();
            $t->string('path', 512)->nullable();
            $t->string('message', 4096)->nullable();
            $t->tinyInteger('status')->index()->nullable();
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('tasks');
    }
}