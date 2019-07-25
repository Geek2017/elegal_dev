<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('case_project_name');
            $table->string('docket_no_venue');
            $table->string('reporter');
            $table->string('gr_title');
            $table->string('client');
            $table->string('ars_date');
            $table->string('time_start');
            $table->string('time_finnish');
            $table->string('duration');
            $table->integer('sr_no');
            $table->string('billing_instruction');
            $table->string('billing_entry');
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
        Schema::dropIfExists('ars');
    }
}
