<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 30)->nullable()->unique();
            $table->string('title');
            $table->tinyInteger('level')->unsigned();
            $table->integer('parent')->nullable()->unsigned();
            $table->enum('type', ['A', 'C'])->comment('A=Account, C=Category');
            $table->enum('category_type', ['A', 'L', 'R', 'X', 'Q', 'D'])->comment('A=ssets, L=iability, R=evenue, X=Expense, Q=Equity, D=ividents');
            $table->enum('normal_account_balance', ['D', 'C'])->comment('Debit or Credit');
            $table->boolean('is_cash_type')->unsigned()->default(0)->comment('if TRUE, this is a cash_type(Cash-in-Bank,Cash-on-Hand,Time-deposit), and has cash_account');
            $table->boolean('is_cash_account')->unsigned()->default(0)->comment('formerly: sub_account; if TRUE, this is a cash-account');
            $table->boolean('has_check')->unsigned()->default(0);
            
            $table->boolean('is_default_cash_account')->unsigned()->default(0)->comment('Default cash account of the client(counsel)');
            $table->boolean('is_net_income')->unsigned()->default(0)->comment('Net Income Account.');
            $table->nullableTimestamps();
            $table->engine = 'InnoDb';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
