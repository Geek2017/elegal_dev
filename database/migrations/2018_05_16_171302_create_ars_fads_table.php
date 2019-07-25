<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArsFadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ars_fads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ars_id')->unsigned();
            $table->string('description');
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
        Schema::dropIfExists('ars_fads');
    }
}
