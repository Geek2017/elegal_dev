<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_managements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->text('title')->nullable();
            $table->text('venue')->nullable();
            $table->date('date')->nullable();
            $table->string('number')->nullable();
            $table->enum('class', array('Administrative','Cadastral Case','Criminal','Civil','Collection Retainer','General Retainer','Labor','Special Civil Action','Special Project','Others'))->nullable();
            $table->enum('status', ['Open','Close'])->default('Open');
            $table->integer('counsel_id');
            $table->integer('author')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_managements');
    }
}
