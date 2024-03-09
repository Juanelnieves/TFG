<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staking;

class StakingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Get the first user to associate with the staking

        Staking::create([
            'amount' =>   1000.00,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'rewards' =>   50.00,
            'status' => 'a', 
            'user_id' => $user->id,
        ]);
    }
}
