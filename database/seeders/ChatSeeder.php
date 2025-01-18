<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Carbon\Carbon;

class ChatSeeder extends Seeder
{
    public function run()
    {
        // Create a test agent if not exists
        $agent = User::firstOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Test Agent',
                'password' => bcrypt('password'),
                'role' => 'agent',
            ]
        );

        // Create a test user if not exists
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        // Create 5 test chats
        for ($i = 1; $i <= 5; $i++) {
            $chat = Chat::create([
                'user_id' => $user->id,
                'agent_id' => $agent->id,
                'status' => 'active',
                'created_at' => Carbon::now()->subHours(rand(1, 24)),
            ]);

            // Add some test messages to each chat
            $messages = [
                ['user' => $user->id, 'content' => 'Hello, I need help with my order.'],
                ['user' => $agent->id, 'content' => 'Hi there! I\'d be happy to help. What seems to be the issue?'],
                ['user' => $user->id, 'content' => 'I haven\'t received my order confirmation yet.'],
                ['user' => $agent->id, 'content' => 'I\'ll look into that right away for you.'],
            ];

            foreach ($messages as $index => $msg) {
                Message::create([
                    'chat_id' => $chat->id,
                    'user_id' => $msg['user'],
                    'content' => $msg['content'],
                    'created_at' => Carbon::parse($chat->created_at)->addMinutes($index * 2),
                ]);
            }
        }
    }
}
