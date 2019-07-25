<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountPaidColumnOperationalFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operational_funds', function (Blueprint $table) {
           $table->decimal('total_amount_paid', 10, 2)->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operational_funds', function (Blueprint $table) {
           $table->dropColumn('total_amount_paid', 10, 2);
        });
    }
}
