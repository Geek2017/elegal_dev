<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateArsTableSetNullableOnSomeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE ars MODIFY COLUMN gr_title varchar(50) NULL, MODIFY COLUMN reporter varchar(50) NULL");
        // Schema::table('ars', function($table) {
        //     $table->string('gr_title', 50)->nullable()->change();
        // });
        // Schema::table('ars', function (Blueprint $table) {  
        //     $table->string('gr_title')->nullable()->change();
        //     $table->string('reporter')->nullable()->change();
        // });
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
