<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceReportIdArsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ars', function (Blueprint $table) {
            $table->unsignedInteger('service_report_id')->after('sr_no')->nullable()->comment('use in cash-receipt payment');
            $table->unsignedInteger('case_management_id')->after('id')->nullable();

            $table->foreign('service_report_id')
                ->references('id')->on('service_reports')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('case_management_id')
                ->references('id')->on('case_managements')
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
        Schema::table('ars', function (Blueprint $table) {
            $table->dropForeign('ars_service_report_id_foreign');
            $table->dropForeign('ars_case_management_id_foreign');
            $table->dropColumn('service_report_id');
            $table->dropColumn('case_management_id');
        });
    }
}
