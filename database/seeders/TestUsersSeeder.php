<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create supervisor users
        User::create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor1@example.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        // Create agent users
        User::create([
            'name' => 'Agent 1',
            'email' => 'agent1@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        User::create([
            'name' => 'Agent 2',
            'email' => 'agent2@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        User::create([
            'name' => 'Agent 3',
            'email' => 'agent3@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);
    }
}
