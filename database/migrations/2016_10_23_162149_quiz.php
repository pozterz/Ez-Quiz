<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Quiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200);
            $table->integer('subject_id')->unsigned()->index();
            $table->integer('level');
            $table->time('quiz_time');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();
        });

        Schema::table('quizzes',function(Blueprint $table){
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quizzes');
    }
}
