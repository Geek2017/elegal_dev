<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargeablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chargeables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sr_id')->unsigned()->nullable();
            $table->integer('fee_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->integer('author')->nullable();
            $table->timestamps();

            $table->foreign('sr_id')
                ->references('id')->on('service_reports')
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
        Schema::dropIfExists('chargeables');
    }
}
