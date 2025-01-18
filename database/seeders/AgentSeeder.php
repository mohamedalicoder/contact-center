<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test agent if no online agents exist
        if (!User::where('role', 'agent')->where('status', 'online')->exists()) {
            // Check if we have any offline agents we can set online
            $offlineAgent = User::where('role', 'agent')->where('status', 'offline')->first();
            
            if ($offlineAgent) {
                $offlineAgent->update(['status' => 'online']);
            } else {
                // Create a new agent if none exist
                User::create([
                    'name' => 'Test Agent',
                    'email' => 'agent@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'agent',
                    'status' => 'online',
                ]);
            }
        }
    }
}
