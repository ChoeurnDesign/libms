<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Library Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0965678390',
                'address' => '123 Library Street, Book City, BC 12345',
            ]
        );

        // Create regular user
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'John Reader',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '016765431',
                'address' => '456 Reader Avenue, Study Town, ST 67890',
            ]
        );


    }
}
