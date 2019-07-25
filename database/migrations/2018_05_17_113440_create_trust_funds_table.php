<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTrustFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trust_funds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->decimal('deposit',10,2)->default(0);
            $table->decimal('credit',10,2)->default(0);
            $table->decimal('balance',10,2)->default(0);
            $table->integer('billing_id')->nullable();
            $table->string('description')->nullable();
            $table->integer('author')->nullable();


//            $table->decimal('total',10,2)->default(0);
//            $table->integer('merged_to')->nullable();
//            $table->decimal('chargeable_expense',10,2)->default(0);
            $table->timestamps();
            $table->foreign('client_id')
                ->references('id')->on('clients')
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
        Schema::dropIfExists('trust_funds');
    }
}