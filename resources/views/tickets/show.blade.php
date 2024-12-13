<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ticket') }} #{{ $ticket->id }}
            </h2>
            <div>
                <a href="{{ route('tickets.edit', $ticket) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit Ticket
                </a>
                <a href="{{ route('tickets.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Ticket Status Banner -->
                    <div class="mb-6 p-4 rounded-lg {{ 
                        $ticket->status === 'open' ? 'bg-green-100' : 
                        ($ticket->status === 'closed' ? 'bg-gray-100' : 'bg-yellow-100') 
                    }}">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-semibold">Status:</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                                       ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                                <span class="ml-4 font-semibold">Priority:</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                       ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                            @if($ticket->status !== 'closed')
                                <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Are you sure you want to close this ticket?')">
                                        Close Ticket
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Ticket Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Subject</p>
                                <p class="mt-1">{{ $ticket->subject }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Contact</p>
                                <p class="mt-1">
                                    <a href="{{ route('contacts.show', $ticket->contact) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $ticket->contact->name }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Assigned To</p>
                                <p class="mt-1">{{ $ticket->assignedTo->name ?? 'Unassigned' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Created</p>
                                <p class="mt-1">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            @if($ticket->due_date)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Due Date</p>
                                    <p class="mt-1 {{ $ticket->due_date < now() && $ticket->status !== 'closed' ? 'text-red-600' : '' }}">
                                        {{ $ticket->due_date->format('M d, Y H:i') }}
                                    </p>
                                </div>
                            @endif
                            @if($ticket->resolved_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Resolved</p>
                                    <p class="mt-1">{{ $ticket->resolved_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Description</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            {{ $ticket->description }}
                        </div>
                    </div>

                    <!-- Resolution (if closed) -->
                    @if($ticket->status === 'closed' && $ticket->resolution)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Resolution</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                {{ $ticket->resolution }}
                            </div>
                        </div>
                    @endif

                    <!-- Related Calls -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Related Calls</h3>
                            <a href="{{ route('calls.create', ['contact_id' => $ticket->contact_id]) }}" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Log New Call
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($ticket->contact->calls()->latest()->take(5)->get() as $call)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $call->type === 'inbound' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ ucfirst($call->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $call->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($call->status === 'missed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($call->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $call->duration }} seconds
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $call->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $call->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('calls.show', $call) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No calls recorded yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Activity Timeline</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Ticket created by <span class="font-medium text-gray-900">{{ $ticket->user->name }}</span></p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $ticket->created_at->format('M d, Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @if($ticket->status === 'closed')
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Ticket closed</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $ticket->resolved_at->format('M d, Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
