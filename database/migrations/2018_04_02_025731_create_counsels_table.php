<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counsels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('lawyer_type');
            $table->string('lawyer_code');
            $table->integer('author')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counsels');
    }
}
