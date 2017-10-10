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
            $t->string('url', 512);
            $t->string('path', 512);
            $t->tinyInteger('status');
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('tasks');
    }
}