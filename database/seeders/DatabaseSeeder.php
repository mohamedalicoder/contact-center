<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\TestDataSeeder;
use Database\Seeders\TestUsersSeeder;
use Database\Seeders\AgentSeeder;
use Database\Seeders\ChatMessageSeeder;
use Database\Seeders\ChatSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            TestDataSeeder::class,
            TestUsersSeeder::class,
            AgentSeeder::class,
            ChatMessageSeeder::class,
            ChatSeeder::class,
        ]);
    }
}
