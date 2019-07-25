<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingColumnCashReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_receipts', function (Blueprint $table) {
            $table->unsignedInteger('biling_id')->after('walk_in_charge_slip_id')->nullable()->comment('billing date');
            
            $table->foreign('biling_id')
                ->references('id')->on('billings')
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
        Schema::table('cash_receipts', function (Blueprint $table) {
            $table->dropForeign('cash_receipts_biling_id_foreign');
            $table->dropColumn('cash_receipts_biling_id_foreign');
        });
    }
}
