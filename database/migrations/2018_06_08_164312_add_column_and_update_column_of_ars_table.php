<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAndUpdateColumnOfArsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ars', function (Blueprint $table) {
            $table->enum('billing_instruction_type', ['Non-Billable', 'Billable', 'Appearance', 'Time-Trate'])->nullable();
            $table->smallInteger('billable_time')->default(0);
            $table->smallInteger('ars_no')->default(0);
            
            $table->date('ars_date')->change();
            $table->time('time_start')->change();
            $table->time('time_finnish')->change();

            $table->unsignedBigInteger('client')->change();
            $table->renameColumn('client', 'client_id');

            $table->string('sr_no')->change();

        });

        // \DB::statement("ALTER TABLE ars MODIFY COLUMN client_id int unsigned;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        \DB::statement("ALTER TABLE ars DROP COLUMN billing_instruction_type");

        Schema::table('ars', function ($table) {
            $table->dropColumn('billable_time');
            $table->dropColumn('ars_no');

            $table->string('ars_date')->change();
            $table->string('time_start')->change();
            $table->string('time_finnish')->change();
            
            $table->string('client_id')->change();
            $table->renameColumn('client_id', 'client');

            $table->integer('sr_no')->change();
        });

        // \DB::statement("ALTER TABLE ars MODIFY COLUMN client varchar(255);");
    }
}
