<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manage Tags') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <button onclick="openTagModal()" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Add New Tag
                </button>
            </div>

            @if(session('success'))
                <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 rounded border border-green-400" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="overflow-hidden bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Color</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Contacts</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tags as $tag)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-6 h-6 rounded" style="background-color: {{ $tag->color }}"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tag->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tag->contacts_count }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                    <button onclick="openEditTagModal({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}')"
                                            class="mr-3 text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete this tag?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Tag Modal -->
    <div id="tagModal" class="hidden overflow-y-auto fixed inset-0 w-full h-full bg-gray-600 bg-opacity-50">
        <div class="relative top-20 p-5 mx-auto w-96 bg-white rounded-md border shadow-lg">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Tag</h3>
                <form id="tagForm" method="POST" action="{{ route('tags.store') }}" class="mt-4">
                    @csrf
                    <div id="methodField"></div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-gray-700" for="name">
                            Tag Name
                        </label>
                        <input type="text" name="name" id="tagName" required
                               class="px-3 py-2 w-full leading-tight text-gray-700 rounded border shadow appearance-none focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-bold text-gray-700" for="color">
                            Color
                        </label>
                        <input type="color" name="color" id="tagColor" required
                               class="px-3 py-2 w-full leading-tight text-gray-700 rounded border shadow appearance-none focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeTagModal()"
                                class="px-4 py-2 mr-2 text-white bg-gray-500 rounded-lg">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function openTagModal() {
        document.getElementById('modalTitle').textContent = 'Add New Tag';
        document.getElementById('tagForm').action = "{{ route('tags.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('tagName').value = '';
        document.getElementById('tagColor').value = '#6B7280';
        document.getElementById('tagModal').classList.remove('hidden');
    }

    function openEditTagModal(id, name, color) {
        document.getElementById('modalTitle').textContent = 'Edit Tag';
        document.getElementById('tagForm').action = `/tags/${id}`;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('tagName').value = name;
        document.getElementById('tagColor').value = color;
        document.getElementById('tagModal').classList.remove('hidden');
    }

    function closeTagModal() {
        document.getElementById('tagModal').classList.add('hidden');
    }
    </script>
    @endpush
</x-app-layout>