<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user - only create if doesn't exist
        if (!User::where('email', '2300717714@mubs.ac.ug')->exists()) {
            User::create([
                'name' => 'Nasser Sseru',
                'email' => '2300717714@mubs.ac.ug',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Regular user - only create if doesn't exist
        if (!User::where('email', 'user@mamtours.com')->exists()) {
            User::create([
                'name' => 'Wilberforce Kandahura',
                'email' => 'user@mamtours.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }
    }
}
