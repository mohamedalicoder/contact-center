<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Support Requests') }}
            </h2>
            <a href="{{ route('support-requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Request
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Title</th>
                                    <th class="px-4 py-2">Priority</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Created</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2">{{ $request->id }}</td>
                                        <td class="border px-4 py-2">{{ $request->title }}</td>
                                        <td class="border px-4 py-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $request->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $request->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                                {{ $request->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $request->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $request->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $request->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $request->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2">{{ $request->created_at->diffForHumans() }}</td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('support-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                            <a href="{{ route('support-requests.edit', $request) }}" class="text-green-600 hover:text-green-900">Edit</a>
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
</x-app-layout>
