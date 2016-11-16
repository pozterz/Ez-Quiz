<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeChoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quiz_qas_id')->unsigned()->index();
            $table->enum('isCorrect',['true','false'])->default('false');
            $table->string('choice-text',100);
        });
        Schema::table('choices',function(Blueprint $table){
            $table->foreign('quiz_qas_id')->references('id')->on('quiz_qas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choices');
    }
}
