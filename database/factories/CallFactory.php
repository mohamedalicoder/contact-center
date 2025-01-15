<?php

namespace Database\Factories;

use App\Models\Call;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class CallFactory extends Factory
{
    protected $model = Call::class;

    public function definition()
    {
        $user = User::factory()->create(['role' => 'user']);
        $agent = User::factory()->create(['role' => 'agent']);
        $startedAt = now()->subMinutes(rand(1, 60));
        $endedAt = $startedAt->copy()->addMinutes(rand(1, 30));

        return [
            'contact_id' => Contact::factory(),
            'user_id' => $user->id,
            'agent_id' => $agent->id,
            'direction' => $this->faker->randomElement(['inbound', 'outbound']),
            'status' => $this->faker->randomElement(['queued', 'in_progress', 'completed', 'missed', 'failed']),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration' => $startedAt->diffInSeconds($endedAt),
            'notes' => $this->faker->paragraph,
            'recording_url' => $this->faker->url,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inbound()
    {
        return $this->state(function (array $attributes) {
            return [
                'direction' => 'inbound',
            ];
        });
    }

    public function outbound()
    {
        return $this->state(function (array $attributes) {
            return [
                'direction' => 'outbound',
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            $startedAt = now()->subMinutes(rand(1, 60));
            $endedAt = $startedAt->copy()->addMinutes(rand(1, 30));
            
            return [
                'status' => 'completed',
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'duration' => $startedAt->diffInSeconds($endedAt),
            ];
        });
    }

    public function missed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'missed',
                'started_at' => null,
                'ended_at' => null,
                'duration' => null,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'in_progress',
                'started_at' => now()->subMinutes(rand(1, 30)),
                'ended_at' => null,
                'duration' => null,
            ];
        });
    }

    public function withAgent(User $agent)
    {
        return $this->state(function (array $attributes) use ($agent) {
            return [
                'agent_id' => $agent->id,
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
