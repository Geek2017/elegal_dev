<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseCounselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_counsels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('case_id')->unsigned()->nullable();
            $table->integer('counsel_id');
            $table->boolean('lead')->default(0);
            $table->timestamps();

            $table->foreign('case_id')
                ->references('id')->on('case_managements')
                ->onDelete('cascade');

//            $table->foreign('sr_id')
//                ->references('id')->on('service_reports')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_counsels');
    }
}
