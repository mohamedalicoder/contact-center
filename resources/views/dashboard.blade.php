<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Active Tickets -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Active Tickets</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ App\Models\Ticket::where('status', '!=', 'closed')->count() }}</p>
                        </div>

                        <!-- Today's Calls -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Today's Calls</h3>
                            <p class="text-3xl font-bold text-green-600">{{ App\Models\Call::whereDate('created_at', today())->count() }}</p>
                        </div>

                        <!-- Total Contacts -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Total Contacts</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ App\Models\Contact::count() }}</p>
                        </div>

                        <!-- Resolved Today -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Resolved Today</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ App\Models\Ticket::whereDate('resolved_at', today())->count() }}</p>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(array_merge(
                                        App\Models\Call::latest()->take(5)->get()->map(function($call) {
                                            return [
                                                'type' => 'Call',
                                                'description' => $call->type . ' call with ' . $call->contact->name,
                                                'created_at' => $call->created_at
                                            ];
                                        })->toArray(),
                                        App\Models\Ticket::latest()->take(5)->get()->map(function($ticket) {
                                            return [
                                                'type' => 'Ticket',
                                                'description' => $ticket->subject,
                                                'created_at' => $ticket->created_at
                                            ];
                                        })->toArray()
                                    ) as $activity)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $activity['type'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $activity['description'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $activity['created_at']->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
