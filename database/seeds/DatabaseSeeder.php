<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
//        $this->call(ClientTableSeeder::class);
        $this->call(CounselTableSeeder::class);
        $this->call(FeeCategoryTableSeeder::class);
        $this->call(AccountTableSeeder::class);
        $this->call(NoteTableSeeder::class);
        // put this seeder after AccountTableSeeder
        $this->call(InsertFeeDataAccountIdTableSeeder::class);
    }
}
