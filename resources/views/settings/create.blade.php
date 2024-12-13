<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Add New Setting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('settings.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Setting Name</label>
                            <input type="text" name="name" id="name" required
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('name') }}"
                                   placeholder="Enter setting name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="key" class="block text-sm font-medium text-gray-700">Setting Key</label>
                            <input type="text" name="key" id="key" required
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('key') }}"
                                   placeholder="Enter setting key (e.g., app.timezone)">
                            @error('key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Setting Type</label>
                            <select name="type" id="type" required
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select Type</option>
                                <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="number" {{ old('type') === 'number' ? 'selected' : '' }}>Number</option>
                                <option value="boolean" {{ old('type') === 'boolean' ? 'selected' : '' }}>Boolean</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="group" class="block text-sm font-medium text-gray-700">Group</label>
                            <input type="text" name="group" id="group" required
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('group') }}"
                                   placeholder="Enter setting group (e.g., general, security)">
                            @error('group')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700">Default Value</label>
                            <input type="text" name="value" id="value"
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('value') }}"
                                   placeholder="Enter default value">
                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="label" class="block text-sm font-medium text-gray-700">Default Label</label>
                            <input type="text" name="label" id="label"
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('label') }}"
                                   placeholder="Enter default label">
                            @error('label')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                      placeholder="Enter setting description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_public" class="text-indigo-600 rounded border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       {{ old('is_public') ? 'checked' : '' }}>
                                <span class="ml-2">Make this setting public (accessible to non-admin users)</span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('settings.index') }}"
                               class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase bg-gray-300 rounded-md border border-transparent ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring disabled:opacity-25">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-indigo-600 rounded-md border border-transparent ring-indigo-300 transition duration-150 ease-in-out hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring disabled:opacity-25">
                                Create Setting
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
            const valueInput = document.getElementById('value');

            typeSelect.addEventListener('change', function() {
                if (this.value === 'boolean') {
                    valueInput.type = 'checkbox';
                    valueInput.classList.add('rounded');
                } else if (this.value === 'number') {
                    valueInput.type = 'number';
                } else {
                    valueInput.type = 'text';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
