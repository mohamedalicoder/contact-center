<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Custom Fields') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <button onclick="openFieldModal()" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Add Custom Field
                </button>
            </div>

            @if(session('success'))
                <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 rounded border border-green-400" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="overflow-hidden bg-white rounded-lg shadow">
                <!-- Rest of your table content remains the same -->
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Your existing table content -->
                </table>
            </div>
        </div>
    </div>

    <!-- Your existing modal content -->
    <!-- Create/Edit Custom Field Modal -->
    <div id="fieldModal" class="hidden overflow-y-auto fixed inset-0 w-full h-full bg-gray-600 bg-opacity-50">
        <!-- Your existing modal content -->
    </div>

    @push('scripts')
    <script>
        // Your existing JavaScript functions
    </script>
    @endpush
</x-app-layout>