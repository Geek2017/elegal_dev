<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCaseIdServiceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_reports', function (Blueprint $table) {
           $table->unsignedInteger('case_id')->nullable()->after('client_id');

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
        Schema::table('service_reports', function (Blueprint $table) {
            $table->dropForeign('service_reports_case_id_foreign');
            $table->dropColumn('case_id');
        });
    }
}
