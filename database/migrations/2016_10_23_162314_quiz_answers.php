<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuizAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quiz_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('point')->unsigned();
            $table->timestamps();
        });

        Schema::table('quiz_answers',function(Blueprint $table){
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quiz_answers');
    }
}
