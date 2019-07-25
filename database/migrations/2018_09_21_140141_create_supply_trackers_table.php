<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_trackers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('supply_id');
            $table->integer('in')->nullable();
            $table->integer('out')->nullable();
            $table->integer('balance')->default(0);
            $table->integer('short')->nullable()->comment('if the supply was short due to unknown reasons');
            $table->string('remarks')->nullable()->comment('for referral we add remarks on every transaction');
            $table->timestamps();

            $table->foreign('supply_id')
                ->references('id')->on('supplies')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_trackers');
    }
}
