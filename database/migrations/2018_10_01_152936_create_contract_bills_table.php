<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id');
            $table->integer('billing_id');
            $table->integer('case_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->boolean('special_billing')->default(0);
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
        Schema::dropIfExists('contract_bills');
    }
}
