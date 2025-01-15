<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $agent;
    protected $user;
    protected $contact;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->agent = User::factory()->agent()->create();
        $this->user = User::factory()->user()->create();
        $this->contact = Contact::factory()->create();
    }

    public function test_can_create_ticket()
    {
        $this->actingAs($this->admin);
        
        $ticketData = [
            'contact_id' => $this->contact->id,
            'subject' => 'Test Ticket',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'open'
        ];

        $response = $this->post(route('tickets.store'), $ticketData);
        
        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test Ticket',
            'contact_id' => $this->contact->id,
            'user_id' => $this->admin->id
        ]);
    }

    public function test_can_assign_ticket_to_agent()
    {
        $this->actingAs($this->admin);
        
        $ticket = Ticket::factory()
            ->withUser($this->user)
            ->create([
                'contact_id' => $this->contact->id,
                'status' => 'open'
            ]);

        $response = $this->post(route('tickets.assign', $ticket), [
            'agent_id' => $this->agent->id
        ]);
        
        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'assigned_to' => $this->agent->id,
            'status' => 'in_progress'
        ]);
    }

    public function test_can_update_ticket_status()
    {
        $this->actingAs($this->agent);
        
        $ticket = Ticket::factory()
            ->withUser($this->user)
            ->withAgent($this->agent)
            ->create([
                'contact_id' => $this->contact->id,
                'status' => 'open',
                'subject' => 'Test Ticket',
                'description' => 'Test Description',
                'priority' => 'high'
            ]);

        $response = $this->patch(route('tickets.update', $ticket), [
            'subject' => 'Test Ticket',
            'description' => 'Test Description',
            'priority' => 'high',
            'status' => 'in_progress',
            'assigned_to' => $this->agent->id
        ]);
        
        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'in_progress'
        ]);
    }

    public function test_agent_can_only_see_assigned_tickets()
    {
        $this->actingAs($this->agent);
        
        // Create tickets assigned to this agent
        Ticket::factory()
            ->withUser($this->user)
            ->withAgent($this->agent)
            ->count(3)
            ->create();

        // Create tickets assigned to other agents
        Ticket::factory()
            ->withUser($this->user)
            ->count(2)
            ->create();

        $response = $this->get(route('tickets.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('tickets');
        $this->assertEquals(3, $response->viewData('tickets')->count());
    }

    public function test_admin_can_see_all_tickets()
    {
        $this->actingAs($this->admin);
        
        Ticket::factory()
            ->withUser($this->user)
            ->count(5)
            ->create();

        $response = $this->get(route('tickets.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('tickets');
        $this->assertEquals(5, $response->viewData('tickets')->count());
    }

    public function test_validates_required_fields_for_ticket_creation()
    {
        $this->actingAs($this->admin);
        
        $response = $this->post(route('tickets.store'), []);
        
        $response->assertSessionHasErrors(['contact_id', 'subject', 'description', 'priority', 'status']);
    }

    public function test_can_close_ticket()
    {
        $this->actingAs($this->agent);
        
        $ticket = Ticket::factory()
            ->withUser($this->user)
            ->withAgent($this->agent)
            ->inProgress()
            ->create();

        $response = $this->post(route('tickets.close', $ticket), [
            'resolution' => 'Issue resolved'
        ]);
        
        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'closed',
            'resolution' => 'Issue resolved'
        ]);
    }
}
