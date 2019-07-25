<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->decimal('default_amount',8,2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fee_id')
                ->references('id')->on('fees')
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
        Schema::dropIfExists('fee_descriptions');
    }
}
