<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class ChatMessageSeeder extends Seeder
{
    public function run()
    {
        // Get users with different roles
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        })->get();
        
        $agents = User::whereHas('roles', function($q) {
            $q->where('name', 'agent');
        })->get();

        if ($agents->isEmpty()) {
            // Create a test agent if none exists
            $agent = User::create([
                'name' => 'Test Agent',
                'email' => 'agent@example.com',
                'password' => bcrypt('password'),
            ]);
            $agent->assignRole('agent');
            $agents = collect([$agent]);
        }

        // Create some chats with messages
        foreach ($users as $user) {
            // Create 2 chats for each user
            for ($i = 0; $i < 2; $i++) {
                $chat = Chat::create([
                    'user_id' => $user->id,
                    'agent_id' => $agents->random()->id,
                    'status' => $i === 0 ? 'active' : 'closed',
                ]);

                // Add some messages to each chat
                $this->createMessages($chat, $user);
            }
        }
    }

    private function createMessages($chat, $user)
    {
        $messages = [
            ['content' => 'Hello, I need help with my account.', 'sender_type' => 'user'],
            ['content' => 'Hi there! I\'d be happy to help. What seems to be the issue?', 'sender_type' => 'agent'],
            ['content' => 'I can\'t access my dashboard.', 'sender_type' => 'user'],
            ['content' => 'Let me check that for you. Can you tell me what error message you\'re seeing?', 'sender_type' => 'agent'],
            ['content' => 'It says "Access Denied"', 'sender_type' => 'user'],
            ['content' => 'I\'ve reset your access permissions. Please try logging in again.', 'sender_type' => 'agent'],
            ['content' => 'Thank you, it works now!', 'sender_type' => 'user'],
            ['content' => 'You\'re welcome! Is there anything else I can help you with?', 'sender_type' => 'agent'],
        ];

        $createdAt = now()->subHours(rand(1, 24));

        foreach ($messages as $index => $messageData) {
            Message::create([
                'chat_id' => $chat->id,
                'user_id' => $messageData['sender_type'] === 'user' ? $user->id : $chat->agent_id,
                'content' => $messageData['content'],
                'sender_type' => $messageData['sender_type'],
                'created_at' => $createdAt->addMinutes(rand(1, 5)),
                'updated_at' => $createdAt,
            ]);
        }
    }
}
