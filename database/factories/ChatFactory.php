<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'admin_id' => User::factory()->create(['role' => 'admin'])->id,
            'status' => $this->faker->randomElement(['active', 'closed']),
            'started_at' => now(),
            'ended_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'active',
                'ended_at' => null,
            ];
        });
    }

    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'closed',
                'ended_at' => now(),
            ];
        });
    }
}
