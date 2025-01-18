<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6">Start a New Chat</h2>
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="chat-form" action="{{ route('chat.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            >
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            >
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">How can we help you?</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Type your message here..."
                                required
                            ></textarea>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex justify-center px-4 py-2 w-full text-sm font-medium text-white bg-orange-600 rounded-md border border-transparent shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                Start Chat
                            </button>
                        </div>
                    </form>

                    <div id="error-message" class="hidden mt-4">
                        <div class="px-4 py-3 text-red-700 bg-red-100 rounded border border-red-400">
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('chat-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const errorDiv = document.getElementById('error-message');
            const errorText = errorDiv.querySelector('p');
            errorDiv.classList.add('hidden');

            const formData = new FormData(form);
            const jsonData = {};
            for (const [key, value] of formData.entries()) {
                jsonData[key] = value;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to start chat');
                }

                if (data.success) {
                    window.location.href = `/chats/${data.chat.id}`;
                }
            } catch (error) {
                console.error('Error:', error);
                errorText.textContent = error.message;
                errorDiv.classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>