<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_contacts_list()
    {
        $this->actingAs($this->user);
        Contact::factory()->count(5)->create();

        $response = $this->get(route('contacts.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
        $response->assertViewHas('contacts');
    }

    public function test_admin_can_create_contact()
    {
        $this->actingAs($this->user);
        
        $contactData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'notes' => $this->faker->sentence
        ];

        $response = $this->post(route('contacts.store'), $contactData);
        
        $response->assertRedirect(route('contacts.index'));
        $this->assertDatabaseHas('contacts', [
            'email' => $contactData['email']
        ]);
    }

    public function test_admin_can_update_contact()
    {
        $this->actingAs($this->user);
        $contact = Contact::factory()->create();
        
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '1234567890',
            'notes' => 'Updated notes'
        ];

        $response = $this->put(route('contacts.update', $contact), $updatedData);
        
        $response->assertRedirect(route('contacts.show', $contact));
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Updated Name'
        ]);
    }

    public function test_admin_can_delete_contact()
    {
        $this->actingAs($this->user);
        $contact = Contact::factory()->create();

        $response = $this->delete(route('contacts.destroy', $contact));
        
        $response->assertRedirect(route('contacts.index'));
        $this->assertSoftDeleted('contacts', [
            'id' => $contact->id
        ]);
    }

    public function test_validates_required_fields_for_contact_creation()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('contacts.store'), []);
        
        $response->assertSessionHasErrors(['name', 'email', 'phone']);
    }

    public function test_validates_unique_email_for_contact()
    {
        $this->actingAs($this->user);
        $existingContact = Contact::factory()->create();
        
        $response = $this->post(route('contacts.store'), [
            'name' => $this->faker->name,
            'email' => $existingContact->email,
            'phone' => $this->faker->phoneNumber
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }
}
