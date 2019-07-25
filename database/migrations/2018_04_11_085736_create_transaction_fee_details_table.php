<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionFeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_fee_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('transaction_id')->unsigned();
            $table->integer('case_id')->unsigned()->nullable();
            $table->integer('client_id');
            $table->integer('contract_id');
            $table->integer('fee_id');
            $table->integer('counsel_id')->nullable();
            $table->enum('charge_type', array('amount','document','time','installment','percentage'))->nullable();
            $table->boolean('special_billing')->default(0);
            $table->integer('billing_id')->nullable();

            // for documents
            $table->integer('free_page')->default(0);
            $table->decimal('excess_rate',10,2)->default(0);
            $table->decimal('cap_value',10,2)->default(0);

            // time
            $table->integer('minutes')->default(0);

            // installment
            $table->decimal('installment',10,2)->default(0);

            // percentage;
            $table->integer('percentage')->default(0);

            // amount only / other charges
            $table->decimal('amount',10,2)->default(0);

            // total from service reports
            $table->decimal('total',10,2)->default(0);


            $table->integer('author')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onDelete('cascade');

            $table->foreign('case_id')
                ->references('id')->on('case_managements')
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
        Schema::dropIfExists('transaction_fee_details');
    }
}