<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Queue Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Agent Status Section -->
            <div class="overflow-hidden mb-6 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="mb-4 text-lg font-semibold">Agent Status</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="p-4 bg-green-100 rounded-lg">
                            <p class="text-sm text-gray-600">Available Agents</p>
                            <p class="text-2xl font-bold">{{ $metrics['available_agents'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-yellow-100 rounded-lg">
                            <p class="text-sm text-gray-600">Busy Agents</p>
                            <p class="text-2xl font-bold">{{ $metrics['busy_agents'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-red-100 rounded-lg">
                            <p class="text-sm text-gray-600">Offline Agents</p>
                            <p class="text-2xl font-bold">{{ $metrics['offline_agents'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queue Statistics Section -->
            <div class="overflow-hidden mb-6 bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="mb-4 text-lg font-semibold">Queue Statistics</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div class="p-4 bg-blue-100 rounded-lg">
                            <p class="text-sm text-gray-600">Total Items</p>
                            <p class="text-2xl font-bold">{{ $metrics['total_items'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-purple-100 rounded-lg">
                            <p class="text-sm text-gray-600">Waiting Items</p>
                            <p class="text-2xl font-bold">{{ $metrics['waiting_items'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-indigo-100 rounded-lg">
                            <p class="text-sm text-gray-600">In Progress</p>
                            <p class="text-2xl font-bold">{{ $metrics['in_progress_items'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-green-100 rounded-lg">
                            <p class="text-sm text-gray-600">Completed Today</p>
                            <p class="text-2xl font-bold">{{ $metrics['completed_today'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queue Items Table -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="mb-4 text-lg font-semibold">Active Queue Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Agent</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Wait Time</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($queueItems ?? [] as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->customer_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $item->status === 'waiting' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($item->status === 'in_progress' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->agent->name ?? 'Unassigned' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->wait_time }}</td>
                                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            <a href="{{ route('queue.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No queue items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>