<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $report->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('reports.download', $report) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    {{ __('Download Report') }}
                </a>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    {{ __('Back to Reports') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Report Meta Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Report Type</div>
                            <div class="mt-1 text-lg font-semibold">{{ ucfirst($report->type) }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Date Range</div>
                            <div class="mt-1 text-lg font-semibold">
                                {{ $report->date_from->format('M d, Y') }} - {{ $report->date_to->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Generated By</div>
                            <div class="mt-1 text-lg font-semibold">{{ $report->user->name }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500">Generated At</div>
                            <div class="mt-1 text-lg font-semibold">{{ $report->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Report Content -->
                    <div class="space-y-6">
                        @if($report->type === 'calls')
                            <!-- Calls Report Content -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-4 py-5 sm:p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Call Statistics</h3>
                                    
                                    <!-- Summary Cards -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Total Calls</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['total_calls'] ?? 0 }}</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Average Duration</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['avg_duration'] ?? '0 min' }}</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Success Rate</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['success_rate'] ?? '0%' }}</div>
                                        </div>
                                    </div>

                                    <!-- Detailed Statistics -->
                                    @if(isset($report->data['calls_by_status']))
                                        <div class="mt-6">
                                            <h4 class="text-md font-medium text-gray-700 mb-3">Calls by Status</h4>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <canvas id="callsChart"></canvas>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($report->type === 'tickets')
                            <!-- Tickets Report Content -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-4 py-5 sm:p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Statistics</h3>
                                    
                                    <!-- Summary Cards -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Total Tickets</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['total_tickets'] ?? 0 }}</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Average Resolution Time</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['avg_resolution_time'] ?? '0 hrs' }}</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Resolution Rate</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['resolution_rate'] ?? '0%' }}</div>
                                        </div>
                                    </div>

                                    <!-- Detailed Statistics -->
                                    @if(isset($report->data['tickets_by_status']))
                                        <div class="mt-6">
                                            <h4 class="text-md font-medium text-gray-700 mb-3">Tickets by Status</h4>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <canvas id="ticketsChart"></canvas>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Performance Report Content -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-4 py-5 sm:p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Performance Metrics</h3>
                                    
                                    <!-- Summary Cards -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Average Response Time</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['avg_response_time'] ?? '0 min' }}</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Customer Satisfaction</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['customer_satisfaction'] ?? '0%' }}</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-500">Resolution Rate</div>
                                            <div class="mt-1 text-2xl font-semibold">{{ $report->data['resolution_rate'] ?? '0%' }}</div>
                                        </div>
                                    </div>

                                    <!-- Agent Performance Table -->
                                    @if(isset($report->data['agent_performance']))
                                        <div class="mt-6">
                                            <h4 class="text-md font-medium text-gray-700 mb-3">Agent Performance</h4>
                                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Agent
                                                            </th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Calls Handled
                                                            </th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Avg Response Time
                                                            </th>
                                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Resolution Rate
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach($report->data['agent_performance'] as $agent)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                    {{ $agent['name'] }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                    {{ $agent['calls_handled'] }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                    {{ $agent['avg_response_time'] }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                    {{ $agent['resolution_rate'] }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($report->type === 'calls' && isset($report->data['calls_by_status']))
                const callsCtx = document.getElementById('callsChart').getContext('2d');
                new Chart(callsCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($report->data['calls_by_status'])) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($report->data['calls_by_status'])) !!},
                            backgroundColor: ['#10B981', '#EF4444', '#F59E0B', '#6366F1']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            @endif

            @if($report->type === 'tickets' && isset($report->data['tickets_by_status']))
                const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
                new Chart(ticketsCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($report->data['tickets_by_status'])) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($report->data['tickets_by_status'])) !!},
                            backgroundColor: ['#10B981', '#EF4444', '#F59E0B', '#6366F1']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
