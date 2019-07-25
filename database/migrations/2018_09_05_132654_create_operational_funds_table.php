<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_funds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('billing_id')->unsigned();
            $table->decimal('amount',10,2)->default(0);
//            $table->boolean('paid')->default(0);
//            $table->integer('receipt_id')->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('billing_id')
                ->references('id')->on('billings')
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
        Schema::dropIfExists('operational_funds');
    }
}
