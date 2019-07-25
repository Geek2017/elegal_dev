<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->enum('contract_type', array('special','general'))->nullable();
            $table->string('contract_number')->nullable();
            $table->date('contract_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status',array('Open','Close'))->default('Close');
            $table->decimal('contract_amount',10,2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->text('other_conditions')->nullable();
            $table->integer('author')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')->onDelete('cascade');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
