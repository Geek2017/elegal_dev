<?php

use Illuminate\Database\Seeder;

use App\Fee;
use App\Account;

class InsertFeeDataAccountIdTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parentAccount = Account::whereRaw("title like '%PROFESSIONAl FEE - RECEIVABLE%'")->first();
        Fee::whereNull('deleted_at')->update(['account_id' => $parentAccount->id]);
    }
}
