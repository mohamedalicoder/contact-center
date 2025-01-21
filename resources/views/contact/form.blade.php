<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Get in Touch with Our Support Team</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">We're here to help! Fill out the form below to send us an email and we'll get back to you as soon as possible.</p>
                </div>

                <div class="grid gap-8 md:grid-cols-5">
                    <!-- Contact Form -->
                    <div class="md:col-span-3">
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-8">
                                @if(session('success'))
                                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input type="text" name="name" id="name" required
                                                    class="pl-10 w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                                                    placeholder="John Doe"
                                                    value="{{ old('name') }}">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <input type="email" name="email" id="email" required
                                                    class="pl-10 w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                                                    placeholder="john@example.com"
                                                    value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                            <div class="relative">
                                                <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                </div>
                                                <textarea name="message" id="message" rows="4" required
                                                    class="pl-10 w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                                                    placeholder="Your message...">{{ old('message') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit"
                                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            Send Email
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info Cards -->
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-orange-100 rounded-full">
                                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Quick Response Time</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get answers to your questions within minutes from our support team.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-orange-100 rounded-full">
                                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Secure Communication</h3>
                                    <p class="mt-1 text-sm text-gray-500">Your messages are encrypted and protected with industry-standard security.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
