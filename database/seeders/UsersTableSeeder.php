<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'Juan',
            'email' => 'juan@gmail.com',
            'email_verified_at' => '2024-01-30 10:34:45',
            'password' => Hash::make('password'), 
        ]);    }
}
