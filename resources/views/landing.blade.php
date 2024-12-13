<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="fixed z-50 w-full bg-white border-b border-gray-200 shadow-sm">
            <div class="flex justify-between items-center px-4 mx-auto max-w-7xl h-16 sm:px-6 lg:px-8">
                <div>
                    <span class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</span>
                </div>
                <div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 text-gray-700 hover:text-gray-900">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 ml-4 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Get Started</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>



        <!-- Hero Section -->
        <section class="relative py-20 bg-white">
            <div class="flex flex-col gap-8 items-center px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 lg:flex-row">
                <div class="lg:w-1/2">
                    <h1 class="mb-4 text-4xl font-extrabold leading-tight text-gray-900">
                        Elevate Your <span class="text-indigo-600">Customer Service</span>
                    </h1>
                    <p class="mb-6 text-lg text-gray-600">
                        Transform your contact center operations with our AI-powered solution. Streamline communications, boost productivity, and deliver exceptional customer experiences.
                    </p>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Start Free Trial</a>
                            <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 rounded-lg border border-gray-300 hover:text-gray-900">Login</a>
                        @endauth
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <img class="rounded-lg shadow-lg" src="https://images.unsplash.com/photo-1542744095-291d1f67b221?auto=format&fit=crop&q=80" alt="Contact Center Dashboard">
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-gray-50">
            <div class="grid grid-cols-1 gap-6 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                    <h3 class="text-lg font-medium text-gray-600">Total Calls</h3>
                    <p class="mt-2 text-4xl font-bold text-indigo-600">{{ number_format($stats['total_calls']) }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                    <h3 class="text-lg font-medium text-gray-600">Active Contacts</h3>
                    <p class="mt-2 text-4xl font-bold text-indigo-600">{{ number_format($stats['total_contacts']) }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                    <h3 class="text-lg font-medium text-gray-600">Team Members</h3>
                    <p class="mt-2 text-4xl font-bold text-indigo-600">{{ number_format($stats['total_agents']) }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                    <h3 class="text-lg font-medium text-gray-600">Satisfaction Rate</h3>
                    <p class="mt-2 text-4xl font-bold text-indigo-600">98%</p>
                </div>
            </div>
        </section>


           <!-- New Section -->
           <section class="py-16 bg-indigo-100">
            <div class="px-4 mx-auto max-w-7xl text-center sm:px-6 lg:px-8">
                <h2 class="mb-4 text-3xl font-extrabold text-gray-900">Why Choose Us?</h2>
                <p class="mb-8 text-lg text-gray-700">We deliver solutions tailored to your needs, ensuring excellence in every interaction.</p>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                        <h3 class="text-lg font-medium text-gray-600">Expert Support</h3>
                        <p class="mt-2 text-gray-500">Our team is ready to assist you 24/7.</p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                        <h3 class="text-lg font-medium text-gray-600">Scalable Solutions</h3>
                        <p class="mt-2 text-gray-500">Grow with confidence using our adaptable tools.</p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
                        <h3 class="text-lg font-medium text-gray-600">Proven Results</h3>
                        <p class="mt-2 text-gray-500">Trusted by hundreds of satisfied clients worldwide.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-6 bg-gray-100">
            <div class="px-4 mx-auto max-w-7xl text-center sm:px-6 lg:px-8">
                <p class="text-gray-600">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                
            </div>
        </footer>
    </div>
</body>
</html>
