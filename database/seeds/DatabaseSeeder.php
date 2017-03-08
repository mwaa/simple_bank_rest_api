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
        \DB::table('bank_accounts')->truncate();
        \DB::table('deposits')->truncate();
        \DB::table('withdrawals')->truncate();

        \App\BankAccount::create([
            'name' => 'Mwaa Joseph',
            'address' => 'Nairobi, Kenya',
            'telephone' => '+254 700 000 000',
            'balance' => 0
        ]);
    }
}
