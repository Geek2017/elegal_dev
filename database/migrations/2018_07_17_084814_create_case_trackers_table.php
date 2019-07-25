<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_trackers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('case_management_id')->index();
            $table->date('transaction_date');
            $table->date('due_date');
            $table->string('activities');
            $table->string('action_to_take');
            $table->enum('status', ['P', 'D'])->comment('P = Pending, D = Done');

            $table->timestamps();

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
        Schema::dropIfExists('case_trackers');
    }
}
