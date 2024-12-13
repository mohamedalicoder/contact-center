<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log New Call') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('calls.store') }}" class="space-y-6">
                        @csrf

                        <!-- Contact Selection -->
                        <div>
                            <label for="contact_id" class="block text-sm font-medium text-gray-700">Contact</label>
                            <select id="contact_id" name="contact_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select Contact</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                        {{ $contact->name }} ({{ $contact->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('contact_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Call Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Call Type</label>
                            <select id="type" name="type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Call Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="started_at" class="block text-sm font-medium text-gray-700">Start Time</label>
                            <input type="datetime-local" id="started_at" name="started_at" required
                                value="{{ old('started_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('started_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="ended_at" class="block text-sm font-medium text-gray-700">End Time</label>
                            <input type="datetime-local" id="ended_at" name="ended_at" required
                                value="{{ old('ended_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('ended_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration (Auto-calculated) -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700">Duration (seconds)</label>
                            <input type="number" id="duration" name="duration" readonly
                                value="{{ old('duration') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('calls.index') }}" 
                                class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Log Call
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-calculate duration when start or end time changes
        function calculateDuration() {
            const startTime = document.getElementById('started_at').value;
            const endTime = document.getElementById('ended_at').value;
            
            if (startTime && endTime) {
                const start = new Date(startTime);
                const end = new Date(endTime);
                const duration = Math.round((end - start) / 1000); // Convert to seconds
                
                if (duration >= 0) {
                    document.getElementById('duration').value = duration;
                }
            }
        }

        document.getElementById('started_at').addEventListener('change', calculateDuration);
        document.getElementById('ended_at').addEventListener('change', calculateDuration);
    </script>
    @endpush
</x-app-layout>
