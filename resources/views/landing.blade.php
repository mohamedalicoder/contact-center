<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-color: #FF8C42;
            --secondary-color: #FFD28E;
        }

        .fade-in {
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .hover-grow:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="fixed z-50 w-full bg-white border-b border-gray-200 shadow-sm fade-in">
            <div class="flex justify-between items-center px-4 mx-auto max-w-7xl h-16 sm:px-6 lg:px-8">
                <div>
                    <span class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</span>
                </div>
                <div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-white bg-[var(--primary-color)] rounded-lg hover:bg-[var(--secondary-color)]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 text-gray-700 hover:text-gray-900">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 ml-4 text-white bg-[var(--primary-color)] rounded-lg hover:bg-[var(--secondary-color)]">Get Started</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative py-20 bg-white fade-in">
            <div class="flex flex-col gap-8 items-center px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 lg:flex-row">
                <div class="lg:w-1/2">
                    <h1 class="mb-4 text-4xl font-extrabold leading-tight text-gray-900">
                        Transform Your <span class="text-[var(--primary-color)]">Customer Service</span>
                    </h1>
                    <p class="mb-6 text-lg text-gray-600">
                        Boost efficiency and deliver exceptional experiences with our live agent tools.
                    </p>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-white bg-[var(--primary-color)] rounded-lg hover:bg-[var(--secondary-color)] hover-grow">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="px-4 py-2 text-white bg-[var(--primary-color)] rounded-lg hover:bg-[var(--secondary-color)] hover-grow">Start Free Trial</a>
                            <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 rounded-lg border border-gray-300 hover:text-gray-900 hover-grow">Login</a>
                        @endauth
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <img class="rounded-lg shadow-lg fade-in" src="https://www.liveagent.ae/wp/urlslab-download/be6e009226eec8c8fba9db54bad5ae31/home-benefit-ticketing.png" alt="Live Agent Dashboard">
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-24 bg-gradient-to-b from-[var(--secondary-color)] to-white text-gray-900 fade-in">
            <div class="px-4 mx-auto max-w-7xl text-center sm:px-6 lg:px-8">
                <h2 class="mb-4 text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-orange-400">Core Features</h2>
                <p class="mb-12 text-lg text-gray-600">Everything you need to deliver exceptional customer service</p>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 group hover:shadow-2xl hover:-translate-y-2 hover:bg-orange-50">
                        <div class="p-4 mx-auto mb-6 w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-gray-900">Real-Time Chat</h3>
                        <p class="text-gray-600">Engage with customers instantly through our powerful real-time messaging platform.</p>
                    </div>
                    <div class="p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 group hover:shadow-2xl hover:-translate-y-2 hover:bg-orange-50">
                        <div class="p-4 mx-auto mb-6 w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-gray-900">Analytics Dashboard</h3>
                        <p class="text-gray-600">Monitor and analyze performance metrics to optimize your customer service.</p>
                    </div>
                    <div class="p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 group hover:shadow-2xl hover:-translate-y-2 hover:bg-orange-50">
                        <div class="p-4 mx-auto mb-6 w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-gray-900">Team Collaboration</h3>
                        <p class="text-gray-600">Streamline internal communication and work together seamlessly.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-24 bg-gray-50 fade-in">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h2 class="mb-4 text-4xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-orange-400">What Our Clients Say</h2>
                <p class="mb-12 text-lg text-center text-gray-600">Trusted by companies worldwide</p>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="relative p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute -top-4 right-8 w-8 h-8 text-5xl text-orange-400">"</div>
                        <p class="mb-6 leading-relaxed text-gray-600">"This tool has completely changed the way we interact with our customers! The interface is intuitive and the features are exactly what we needed."</p>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-full"></div>
                            <div class="ml-4">
                                <h3 class="font-bold text-gray-900">John Doe</h3>
                                <p class="text-sm text-gray-500">Customer Service Manager</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute -top-4 right-8 w-8 h-8 text-5xl text-orange-400">"</div>
                        <p class="mb-6 leading-relaxed text-gray-600">"The analytics dashboard is a game-changer for our team. We can now make data-driven decisions to improve our customer service."</p>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-full"></div>
                            <div class="ml-4">
                                <h3 class="font-bold text-gray-900">Jane Smith</h3>
                                <p class="text-sm text-gray-500">Operations Director</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                        <div class="absolute -top-4 right-8 w-8 h-8 text-5xl text-orange-400">"</div>
                        <p class="mb-6 leading-relaxed text-gray-600">"We love the seamless integration and customer support. It's made our workflow much more efficient and our customers are happier."</p>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-full"></div>
                            <div class="ml-4">
                                <h3 class="font-bold text-gray-900">Sarah Lee</h3>
                                <p class="text-sm text-gray-500">Support Team Lead</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

         <!-- Why Choose Us Section -->
         <section class="py-24 bg-gradient-to-b from-white to-orange-50 fade-in">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h2 class="mb-4 text-4xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-orange-400">Why Choose Us</h2>
                <p class="mb-16 text-lg text-center text-gray-600">Advanced features that set us apart</p>

                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                    <div class="relative">
                        <div class="space-y-12">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 p-3 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900">Real-Time Performance</h3>
                                    <p class="text-gray-600">Instant updates and live monitoring of all customer interactions, ensuring no communication is missed.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 p-3 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900">Smart Ticket Management</h3>
                                    <p class="text-gray-600">Automated ticket routing and priority management to streamline customer support workflow.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 p-3 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900">Advanced Analytics</h3>
                                    <p class="text-gray-600">Comprehensive reporting and insights to help optimize your customer service operations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="space-y-12">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 p-3 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900">Team Management</h3>
                                    <p class="text-gray-600">Efficient team coordination with role-based access control and performance tracking.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 p-3 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-300 rounded-xl">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="mb-2 text-xl font-bold text-gray-900">Scheduling & Planning</h3>
                                    <p class="text-gray-600">Smart scheduling tools to manage team shifts and customer appointments effectively.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration & Security Section -->
        <section class="py-24 text-white bg-gray-900 fade-in">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h2 class="mb-4 text-4xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-300">Integration & Security</h2>
                <p class="mb-16 text-lg text-center text-gray-400">Enterprise-grade security with seamless integration capabilities</p>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="p-8 bg-gray-800 rounded-2xl transition-all duration-300 hover:bg-gray-700">
                        <div class="p-4 mb-6 w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-300 rounded-2xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-white">Data Security</h3>
                        <p class="text-gray-400">End-to-end encryption for all communications and data storage with regular security audits.</p>
                    </div>

                    <div class="p-8 bg-gray-800 rounded-2xl transition-all duration-300 hover:bg-gray-700">
                        <div class="p-4 mb-6 w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-300 rounded-2xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-white">API Integration</h3>
                        <p class="text-gray-400">Easy integration with your existing tools through our comprehensive API and webhooks.</p>
                    </div>

                    <div class="p-8 bg-gray-800 rounded-2xl transition-all duration-300 hover:bg-gray-700">
                        <div class="p-4 mb-6 w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-300 rounded-2xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-white">Compliance Ready</h3>
                        <p class="text-gray-400">Built-in compliance with GDPR, HIPAA, and other major data protection regulations.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-16 bg-white fade-in">
            <div class="px-4 mx-auto max-w-7xl text-center sm:px-6 lg:px-8">
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900">Get in Touch</h2>
                <p class="mb-8 text-lg text-gray-600">Have questions? We're here to help.</p>
                <form action="#" method="POST" class="mx-auto max-w-xl">
                    <div class="mb-4">
                        <input type="text" name="name" class="px-4 py-2 w-full rounded-lg border border-gray-300" placeholder="Your Name" required>
                    </div>
                    <div class="mb-4">
                        <input type="email" name="email" class="px-4 py-2 w-full rounded-lg border border-gray-300" placeholder="Your Email" required>
                    </div>
                    <div class="mb-4">
                        <textarea name="message" rows="4" class="px-4 py-2 w-full rounded-lg border border-gray-300" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 text-white bg-[var(--primary-color)] rounded-lg hover:bg-[var(--secondary-color)] hover-grow">Send Message</button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-6 bg-gray-200 fade-in">
            <div class="px-4 mx-auto max-w-7xl text-center sm:px-6 lg:px-8">
                <p class="text-gray-600">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </footer>
    </div>
    <x-chat-popup />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>
</html>
