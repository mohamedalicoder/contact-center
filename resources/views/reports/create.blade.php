<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate New Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Report Name</label>
                            <input type="text" name="title" id="title" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('title') }}"
                                   placeholder="Enter report name">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Report Type</label>
                            <select name="type" id="type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select Report Type</option>
                                <option value="call_report" {{ old('type') === 'call_report' ? 'selected' : '' }}>Calls Report</option>
                                <option value="ticket_report" {{ old('type') === 'ticket_report' ? 'selected' : '' }}>Tickets Report</option>
                                <option value="agent_performance" {{ old('type') === 'agent_performance' ? 'selected' : '' }}>Performance Report</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                <input type="date" name="date_from" id="date_from" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       value="{{ old('date_from') }}">
                                @error('date_from')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                <input type="date" name="date_to" id="date_to" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       value="{{ old('date_to') }}">
                                @error('date_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="additionalFields" class="space-y-6">
                            <!-- Dynamic fields based on report type will be inserted here via JavaScript -->
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('reports.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const additionalFields = document.getElementById('additionalFields');

            typeSelect.addEventListener('change', function() {
                additionalFields.innerHTML = '';

                switch(this.value) {
                    case 'call_report':
                        additionalFields.innerHTML = `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Call Status</label>
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="call_status[]" value="completed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Completed</span>
                                    </label>
                                    <br>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="call_status[]" value="missed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Missed</span>
                                    </label>
                                </div>
                            </div>
                        `;
                        break;
                    case 'ticket_report':
                        additionalFields.innerHTML = `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ticket Status</label>
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="ticket_status[]" value="open" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Open</span>
                                    </label>
                                    <br>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="ticket_status[]" value="closed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Closed</span>
                                    </label>
                                </div>
                            </div>
                        `;
                        break;
                    case 'agent_performance':
                        additionalFields.innerHTML = `
                            <div>
                                <label for="agent" class="block text-sm font-medium text-gray-700">Select Agent</label>
                                <select name="agent_id" id="agent" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Agents</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Metrics to Include</label>
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="metrics[]" value="call_duration" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Average Call Duration</span>
                                    </label>
                                    <br>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="metrics[]" value="response_time" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Average Response Time</span>
                                    </label>
                                    <br>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="metrics[]" value="resolution_rate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Resolution Rate</span>
                                    </label>
                                </div>
                            </div>
                        `;
                        break;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
