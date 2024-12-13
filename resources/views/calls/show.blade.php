<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Call Details') }}
            </h2>
            <div>
                <a href="{{ route('calls.edit', $call) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit Call
                </a>
                <a href="{{ route('calls.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Call Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Call Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Contact</p>
                                <p class="mt-1">
                                    <a href="{{ route('contacts.show', $call->contact) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $call->contact->name }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p class="mt-1">{{ $call->contact->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Type</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $call->type === 'inbound' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($call->type) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $call->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($call->status === 'missed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($call->status) }}
                                    </span>
                                </p>
                            </div>
                            @if($call->status === 'completed')
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Duration</p>
                                    <p class="mt-1">{{ $call->duration }} seconds</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-500">Agent</p>
                                <p class="mt-1">{{ $call->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Date & Time</p>
                                <p class="mt-1">{{ $call->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($call->notes)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Notes</p>
                                <p class="mt-1">{{ $call->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="flex space-x-4">
                            <a href="{{ route('tickets.create', ['contact_id' => $call->contact_id]) }}" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Ticket
                            </a>
                            <a href="{{ route('calls.create', ['contact_id' => $call->contact_id]) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                Log Another Call
                            </a>
                        </div>
                    </div>

                    <!-- Related Tickets -->
                    @if($call->contact->tickets->isNotEmpty())
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Related Tickets</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($call->contact->tickets as $ticket)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->subject }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                                                           ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                                           ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                        {{ ucfirst($ticket->priority) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->created_at->format('M d, Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
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
        </div>
    </div>
</x-app-layout>
