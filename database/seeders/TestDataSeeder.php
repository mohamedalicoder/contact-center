<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Call;
use App\Models\Ticket;
use App\Models\Report;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test agents
        $agents = [];
        for ($i = 1; $i <= 5; $i++) {
            $agent = User::create([
                'name' => "Test Agent $i",
                'email' => "agent$i@test.com",
                'password' => Hash::make('password'),
            ]);
            $agent->assignRole('agent');
            $agents[] = $agent;
        }

        // Create test supervisor
        $supervisor = User::create([
            'name' => 'Test Supervisor',
            'email' => 'supervisor@test.com',
            'password' => Hash::make('password'),
        ]);
        $supervisor->assignRole('supervisor');

        // Create test contacts
        $contacts = [];
        for ($i = 1; $i <= 20; $i++) {
            $contacts[] = Contact::create([
                'name' => "Test Customer $i",
                'email' => "customer$i@test.com",
                'phone' => "+1234567890$i",
                'notes' => "Test contact notes $i",
            ]);
        }

        // Create test calls
        $statuses = ['completed', 'missed', 'voicemail'];
        $types = ['inbound', 'outbound'];
        $now = Carbon::now();
        
        foreach ($agents as $agent) {
            for ($i = 1; $i <= 10; $i++) {
                $startTime = $now->copy()->subDays(rand(1, 30));
                $duration = rand(60, 3600); // Between 1 minute and 1 hour
                $endTime = $startTime->copy()->addSeconds($duration);

                Call::create([
                    'contact_id' => $contacts[array_rand($contacts)]->id,
                    'user_id' => $agent->id,
                    'type' => $types[array_rand($types)],
                    'status' => $statuses[array_rand($statuses)],
                    'duration' => $duration,
                    'notes' => "Test call notes $i",
                    'started_at' => $startTime,
                    'ended_at' => $endTime,
                    'created_at' => $startTime,
                    'updated_at' => $endTime
                ]);
            }
        }

        // Create test tickets
        $priorities = ['low', 'medium', 'high'];
        $ticketStatuses = ['open', 'in_progress', 'closed'];

        foreach ($agents as $agent) {
            for ($i = 1; $i <= 10; $i++) {
                $createdAt = $now->copy()->subDays(rand(1, 30));
                $resolvedAt = rand(0, 1) ? $createdAt->copy()->addHours(rand(1, 48)) : null;
                $status = $resolvedAt ? 'closed' : $ticketStatuses[array_rand($ticketStatuses)];
                $contact = $contacts[array_rand($contacts)];
                $dueDate = $createdAt->copy()->addDays(rand(1, 7));

                Ticket::create([
                    'contact_id' => $contact->id,
                    'user_id' => $supervisor->id,
                    'assigned_to' => $agent->id,
                    'subject' => "Test Ticket $i",
                    'description' => "This is a test ticket description $i",
                    'status' => $status,
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => $dueDate,
                    'resolution' => $resolvedAt ? "Issue resolved: Test resolution $i" : null,
                    'resolved_at' => $resolvedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $resolvedAt ?? $createdAt
                ]);
            }
        }

        // Create test reports
        $reportTypes = ['call_report', 'ticket_report', 'agent_performance'];
        
        for ($i = 1; $i <= 3; $i++) {
            Report::create([
                'title' => "Test Report $i",
                'type' => $reportTypes[$i-1],
                'created_by' => $supervisor->id,
                'start_date' => $now->copy()->subDays(30),
                'end_date' => $now,
                'status' => 'completed',
                'result' => json_encode([
                    'summary' => 'Test report summary',
                    'details' => 'Test report details'
                ])
            ]);
        }
    }
}
