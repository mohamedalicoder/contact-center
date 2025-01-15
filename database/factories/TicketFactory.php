<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        $user = User::factory()->create(['role' => 'user']);
        $agent = User::factory()->create(['role' => 'agent']);

        return [
            'contact_id' => Contact::factory(),
            'user_id' => $user->id,
            'subject' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'assigned_to' => $agent->id,
            'resolution' => null,
            'resolved_at' => null,
            'closed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function open()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'open',
                'closed_at' => null,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'in_progress',
                'closed_at' => null,
            ];
        });
    }

    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'closed',
                'resolution' => $this->faker->paragraph,
                'resolved_at' => now(),
                'closed_at' => now(),
            ];
        });
    }

    public function highPriority()
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => 'high',
            ];
        });
    }

    public function withAgent(User $agent)
    {
        return $this->state(function (array $attributes) use ($agent) {
            return [
                'assigned_to' => $agent->id,
            ];
        });
    }

    public function withUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}
