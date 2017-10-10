<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function(Blueprint $t)
        {
            $t->increments('id')->unsigned();
            $t->text('url', 255);
            $t->text('path', 255);
            $t->text('status', 255);
            $t->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('tasks');
    }
}