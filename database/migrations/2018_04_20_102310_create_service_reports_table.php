<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateServiceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('transaction_id');
            $table->integer('counsel_id')->nullable();
            $table->integer('billing_id')->nullable();
            $table->string('sr_number')->unique();
            $table->integer('fee_detail_id')->unsigned();
            $table->string('fee_description')->nullable();
            $table->string('description')->nullable();
            $table->date('date'); // month to bill?
            $table->string('fas_number')->nullable();
            // for documents
            $table->integer('page_count')->default(0);
            // consumable time
            $table->integer('minutes')->default(0);
            // installment, percentage, amount only
            $table->decimal('total',10,2)->default(0);
            $table->integer('author')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('fee_detail_id')
                ->references('id')->on('transaction_fee_details')
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
        Schema::dropIfExists('service_reports');
    }
}