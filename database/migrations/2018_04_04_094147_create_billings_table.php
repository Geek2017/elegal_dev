<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->nullable();
            $table->string('invoice_number');
            $table->string('bill_number');
            $table->date('bill_date');
            $table->decimal('special', 10, 2)->default(0);
            $table->decimal('general', 10, 2)->default(0);
            $table->decimal('excess', 10, 2)->default(0);
            $table->decimal('bill_amount', 10, 2)->default(0);
            $table->integer('percentage_tax')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('paid')->default(0);
            $table->boolean('special_billing')->default(0);
            $table->text('content')->nullable();
            // merged balance to billing id
            $table->integer('merged_to')->nullable();
            $table->integer('author')->nullable();

//            $table->boolean('is_full_paid')->default(0);

//            $table->decimal('gen_retainers_fee');
//            $table->decimal('chargeable_expense');
//            $table->integer('counsel_id')->unsigned()->nullable();
//            $table->decimal('unpaid_balance',10, 2);
//            $table->decimal('billing_amount');
//            $table->date('last_payment');
//            $table->decimal('last_paid');
//            $table->decimal('gen_retainers_fee');
//            $table->decimal('total_pf');
//            $table->integer('excess_hours');
//            $table->date('current_due');
//            $table->string('adjustment');
//            $table->decimal('balance_forward');
//            $table->boolean('possible_billings');
//            $table->date('period_to');
//            $table->date('period_from');
//            $table->integer('total_hrs');
//            $table->integer('excess_hrs');
//            $table->integer('total_sr');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billings');
    }
}