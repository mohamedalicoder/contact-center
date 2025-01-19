<!-- Chat Button -->
<div id="chat-button" class="fixed bottom-4 right-4 z-[9999]">
    <button onclick="toggleChat()" class="p-4 text-white bg-orange-400 rounded-full shadow-lg hover:bg-orange-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </button>
</div>

<!-- Chat Popup -->
<div id="chat-popup" class="fixed bottom-20 right-4 z-[9999] hidden">
    <div class="flex flex-col w-80 bg-white rounded-lg shadow-xl">
        <!-- Header -->
        <div class="flex justify-between items-center p-4">
            <div class="flex-1 text-center">
                <h3 class="text-lg font-semibold">نحن متاحون على مدار 24/7</h3>
                <p class="text-sm text-gray-600">لا تتردد في التواصل معنا</p>
            </div>
            <button onclick="toggleChat()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Contact Options -->
        <div class="p-4 space-y-4">
            <!-- Contact Form -->
            <a href="{{ route('chat.contact') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-gray-50">
                <div class="p-3 bg-orange-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium text-gray-700">نموذج الاتصال</span>
            </a>

            <!-- Messenger -->
            <a href="{{ config('services.messenger.url', 'https://m.me/') }}" target="_blank" class="flex items-center p-3 rounded-lg transition-colors hover:bg-gray-50">
                <div class="bg-gradient-to-r from-[#00B2FF] to-[#006AFF] rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.145 2 11.243c0 2.936 1.444 5.562 3.741 7.273v3.555l3.412-1.89c.92.256 1.897.394 2.847.394 5.523 0 10-4.145 10-9.243C22 6.145 17.523 2 12 2z"/>
                    </svg>
                </div>
                <span class="ml-3 font-medium text-gray-700">ماسنجر</span>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/qr/4T2VWQDONHCTD1" target="_blank" class="flex items-center p-3 rounded-lg transition-colors hover:bg-gray-50">
                <div class="bg-[#25D366] rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/>
                    </svg>
                </div>
                <span class="ml-3 font-medium text-gray-700">واتساب</span>
            </a>

            <!-- Chat with agent -->
            <a href="{{ route('chat.agent') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-gray-50">
                <div class="p-3 bg-orange-400 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium text-gray-700">تحدث مع موظف</span>
            </a>

            <!-- Chat with bot -->
            <a href="{{ route('chat.bot') }}" class="flex items-center p-3 rounded-lg transition-colors hover:bg-gray-50">
                <div class="p-3 bg-orange-400 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="ml-3 font-medium text-gray-700">المساعد الذكي</span>
            </a>
        </div>
    </div>
</div>

<script>
    function toggleChat() {
        const popup = document.getElementById('chat-popup');
        popup.classList.toggle('hidden');
    }
</script>
