<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatorIdOnCaseManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_managements', function (Blueprint $table) {
            $table->unsignedInteger('creator_id')->after('counsel_id')->nullable();

            $table->foreign('creator_id')
                ->references('id')->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_managements', function (Blueprint $table) {
            $table->dropColumn('creator_id');
        });
    }
}
