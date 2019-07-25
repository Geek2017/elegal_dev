<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReporterColumnWalkInChargeSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('walk_in_charge_slips', function (Blueprint $table) {
            $table->string('reporter')->after('service_specification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('walk_in_charge_slips', function (Blueprint $table) {
            $table->dropColumn('reporter');
        });
    }
}
