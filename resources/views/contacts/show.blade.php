<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contact Details') }}
            </h2>
            <div>
                <a href="{{ route('contacts.edit', $contact) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit Contact
                </a>
                <a href="{{ route('contacts.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Name</p>
                                <p class="mt-1">{{ $contact->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="mt-1">{{ $contact->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p class="mt-1">{{ $contact->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Created</p>
                                <p class="mt-1">{{ $contact->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($contact->notes)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Notes</p>
                                <p class="mt-1">{{ $contact->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Calls -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Calls</h3>
                            <a href="{{ route('calls.create', ['contact_id' => $contact->id]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Log New Call
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($contact->calls()->latest()->take(5)->get() as $call)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($call->type) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $call->duration }} seconds</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($call->status) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $call->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No calls recorded yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Tickets -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Tickets</h3>
                            <a href="{{ route('tickets.create', ['contact_id' => $contact->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Ticket
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($contact->tickets()->latest()->take(5)->get() as $ticket)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->subject }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($ticket->status) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($ticket->priority) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No tickets created yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
