<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id',10);
            $table->string('username', 30)->unique();
            $table->string('password', 200);
            $table->string('name',100);
            $table->string('email',100)->unique();
            $table->enum('type',['teacher','student'])->default('student');
            $table->string('remember_token',100);
            $table->string('ip',15);
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
        Schema::drop('users');
    }
}
