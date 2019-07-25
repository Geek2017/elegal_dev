<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateArsColumnToNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE ars MODIFY COLUMN time_start time NULL");
        \DB::statement("ALTER TABLE ars MODIFY COLUMN time_finnish time NULL");
        \DB::statement("ALTER TABLE ars MODIFY COLUMN duration varchar(191) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
