<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalkInChargeSlipColumnTransactionsFeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_fee_details', function (Blueprint $table) {
            $table->unsignedInteger('walk_in_charge_slip_id')->nullable()->after('transaction_id');

            $table->foreign('walk_in_charge_slip_id')
                ->references('id')->on('walk_in_charge_slips')
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
        Schema::table('transaction_fee_details', function (Blueprint $table) {
            $table->dropForeign('transaction_fee_details_walk_in_charge_slip_id_foreign');
            $table->dropColumn('walk_in_charge_slip_id');
        });
    }
}
