<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArsOosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ars_oos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ars_id')->unsigned();
            $table->string('description');
            $table->string('outcome_outputcol');
            $table->timestamps();


            $table->foreign('ars_id')
            ->references('id')->on('ars')
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
        Schema::dropIfExists('ars_oos');
    }
}
