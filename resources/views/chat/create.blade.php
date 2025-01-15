<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Start Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="p-6">
        <form id="chat-form" class="space-y-4">
            @csrf
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
                <button type="submit" class="inline-flex justify-center px-4 py-2 w-full text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Start Chat
                </button>
            </div>
        </form>
    </div>

    <div id="error-message" class="hidden p-4">
        <div class="px-4 py-3 text-red-700 bg-red-100 rounded border border-red-400">
            <p></p>
        </div>
    </div>

    <script>
        document.getElementById('chat-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const errorDiv = document.getElementById('error-message');
            const errorText = errorDiv.querySelector('p');
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route('chat.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: document.getElementById('message').value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.message || 'Failed to start chat');
                }
            } catch (error) {
                errorDiv.classList.remove('hidden');
                errorText.textContent = error.message;
            }
        });
    </script>
</body>
</html>