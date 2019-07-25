<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkInChargeSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walk_in_charge_slips', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('transaction_id');
            $table->string('charge_slip_no');
            $table->date('transaction_date');
            $table->unsignedInteger('client_id');
            $table->text('address');
            $table->text('service_specification');
            $table->text('details');
            $table->decimal('total_expenses', 15, 4);
            $table->decimal('professional_fees', 15, 4);
            $table->decimal('total_charges', 15, 4);

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('walk_in_charge_slips');
    }
}
