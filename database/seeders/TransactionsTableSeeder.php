<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaction;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user to associate with the transaction

        Transaction::create([
            'type' => 'pool creation',
            'status' => 'completed',
            'amount' =>  500.00,
            'user_id' => $user->id,
        ]);
    }
}
