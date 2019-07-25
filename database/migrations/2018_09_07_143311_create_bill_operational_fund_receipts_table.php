<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillOperationalFundReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_operational_fund_receipts', function (Blueprint $table) {
            $table->unsignedInteger('cash_receipt_id')->index();
            $table->unsignedInteger('billing_id')->nullable()->index();
            $table->unsignedInteger('operational_fund_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('cash_receipt_id')
                ->references('id')->on('cash_receipts')
                ->onDelete('cascade');
            $table->foreign('billing_id')
                ->references('id')->on('billings')
                ->onDelete('cascade');
            $table->foreign('operational_fund_id')
                ->references('id')->on('operational_funds')
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
        Schema::dropIfExists('bill_operational_fund_receipts');
    }
}
