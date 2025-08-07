<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Anbu',
            'email' => 'anbuceo@gmail.com',
            'password' => bcrypt('123456'), // Use a secure password
            'email_verified_at' => now(),
            'role' => 'admin', // Assuming you have a role field
            'remember_token' => null, // Optional, can be set later
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
