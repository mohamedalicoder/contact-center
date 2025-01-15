<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Support Request Details') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('support-requests.edit', $request) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Request
                </a>
                <a href="{{ route('support-requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Request Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Request Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->title }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->description }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Call ID</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->call_id ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Priority</label>
                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $request->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $request->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $request->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($request->priority) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Status Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Status</label>
                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $request->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                    </span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->user->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created At</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->created_at->format('F j, Y g:i A') }}</p>
                                </div>

                                @if($request->resolved_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Resolved At</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->resolved_at->format('F j, Y g:i A') }}</p>
                                </div>
                                @endif

                                @if($request->resolution_notes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Resolution Notes</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $request->resolution_notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
