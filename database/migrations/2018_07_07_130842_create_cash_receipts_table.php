<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->unsignedInteger('walk_in_charge_slip_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->string('cash_receipt_no')->comment('OR no of the transaction');
            $table->datetime('payment_date');
            $table->decimal('amount_due', 15, 4)->nullable();
            $table->decimal('amount_paid', 15, 4);
            $table->decimal('change', 15, 4)->nullable();
            $table->decimal('balance', 15, 4)->nullable();
            $table->boolean('is_trust_fund')->default(0);
            $table->boolean('is_void')->default(0);

            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')->on('accounts')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('walk_in_charge_slip_id')
                ->references('id')->on('walk_in_charge_slips')
                ->onDelete('restrict')->onUpdate('cascade');
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_receipts');
    }
}
