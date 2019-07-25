<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountPaidBillOperationalFundReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_operational_fund_receipts', function (Blueprint $table) {
           $table->decimal('amount_paid', 10, 2)->default(0)->after('operational_fund_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_operational_fund_receipts', function (Blueprint $table) {
           $table->dropColumn('amount_paid');
        });
    }
}
