<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\MessageSent;

class ChatManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_can_create_new_chat()
    {
        $this->actingAs($this->user);
        
        $data = [
            'message' => 'Initial message'
        ];

        $response = $this->post(route('chat.store'), $data);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('chats', [
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id,
            'status' => 'active'
        ]);
    }

    public function test_can_send_message_in_chat()
    {
        Event::fake();
        $this->actingAs($this->user);
        
        $chat = Chat::create([
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        $data = [
            'message' => 'Test Message'
        ];

        $response = $this->post(route('chat.message', $chat), $data);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'content' => 'Test Message'
        ]);
        
        Event::assertDispatched(MessageSent::class);
    }

    public function test_cannot_send_message_in_closed_chat()
    {
        $this->actingAs($this->user);
        
        $chat = Chat::create([
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id,
            'status' => 'closed',
            'started_at' => now(),
            'ended_at' => now(),
        ]);

        $data = [
            'message' => 'Test Message'
        ];

        $response = $this->post(route('chat.message', $chat), $data);
        
        $response->assertStatus(403);
    }

    public function test_admin_can_end_chat()
    {
        $this->actingAs($this->admin);
        
        $chat = Chat::create([
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        $response = $this->post(route('chat.end', $chat));
        
        $response->assertRedirect(route('chat.index'));
        $this->assertDatabaseHas('chats', [
            'id' => $chat->id,
            'status' => 'closed'
        ]);
    }

    public function test_user_cannot_access_other_users_chat()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);
        
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id
        ]);

        $response = $this->get(route('chat.show', $chat));
        
        $response->assertStatus(403);
    }

    public function test_can_view_chat_history()
    {
        $this->actingAs($this->user);
        
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'admin_id' => $this->admin->id
        ]);

        Message::factory()->count(5)->create([
            'chat_id' => $chat->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->get(route('chat.show', $chat));
        
        $response->assertStatus(200);
        $response->assertViewHas('chat');
        $this->assertEquals(5, $chat->messages()->count());
    }
}
