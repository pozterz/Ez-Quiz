<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuizQA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_qas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quiz_id')->unsigned()->index();
            $table->string('question',500);
            $table->integer('duration');
        });

        Schema::table('quiz_qas',function(Blueprint $table){
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quiz_qas');
    }
}
