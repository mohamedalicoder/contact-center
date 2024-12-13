<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Range Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" class="flex space-x-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" type="date" name="start_date" :value="$startDate" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" type="date" name="end_date" :value="$endDate" class="mt-1" />
                        </div>
                        <div class="flex items-end">
                            <x-primary-button>{{ __('Filter') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Call Analytics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Call Analytics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Calls</p>
                            <p class="text-2xl font-bold">{{ $callAnalytics['total_calls'] }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Average Duration</p>
                            <p class="text-2xl font-bold">{{ round($callAnalytics['average_duration'], 2) }} minutes</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Calls by Status</p>
                            <div class="space-y-2 mt-2">
                                @foreach($callAnalytics['calls_by_status'] as $status => $count)
                                    <div class="flex justify-between">
                                        <span class="capitalize">{{ $status }}</span>
                                        <span class="font-semibold">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Analytics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Ticket Analytics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Tickets</p>
                            <p class="text-2xl font-bold">{{ $ticketAnalytics['total_tickets'] }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Open Tickets</p>
                            <p class="text-2xl font-bold">{{ $ticketAnalytics['open_tickets'] }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Average Resolution Time</p>
                            <p class="text-2xl font-bold">{{ round($ticketAnalytics['average_resolution_time'], 1) }} hours</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg col-span-full">
                            <p class="text-sm text-gray-600">Tickets by Priority</p>
                            <div class="space-y-2 mt-2">
                                @foreach($ticketAnalytics['tickets_by_priority'] as $priority => $count)
                                    <div class="flex justify-between">
                                        <span class="capitalize">{{ $priority }}</span>
                                        <span class="font-semibold">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Agent Performance</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Calls</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Call Duration</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tickets</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($agentPerformance as $agent)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $agent->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $agent->calls_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ round($agent->calls_avg_duration, 2) }} minutes</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $agent->tickets_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
