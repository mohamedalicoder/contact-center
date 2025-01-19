<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Active Tickets -->
                <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl transition-all duration-300 hover:shadow-xl hover:scale-105 border border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">المحادثات النشطة</p>
                            <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">{{ $stats['active_chats'] }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Today's Calls -->
                <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl transition-all duration-300 hover:shadow-xl hover:scale-105 border border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">محادثات اليوم</p>
                            <p class="text-4xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">{{ $todayStats['total_chats'] }}</p>
                            <div class="mt-2 text-sm text-gray-500">
                                <span class="text-green-600 font-medium">{{ $todayStats['active_chats'] }}</span> نشط،
                                <span class="text-blue-600 font-medium">{{ $todayStats['ended_chats'] }}</span> منتهي
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-100 to-green-50 rounded-2xl">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Response Time -->
                <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl transition-all duration-300 hover:shadow-xl hover:scale-105 border border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">عدد الموظفين النشطين</p>
                            <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">{{ $stats['active_employees'] }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-50 rounded-2xl">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Customer Satisfaction -->
                <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl transition-all duration-300 hover:shadow-xl hover:scale-105 border border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">المحادثات المنتهية</p>
                            <p class="text-4xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-400 bg-clip-text text-transparent">{{ $stats['ended_chats'] }}</p>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-2xl">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                <!-- Call Volume Chart -->
                <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl border border-gray-100">
                    <h3 class="mb-6 text-xl font-bold text-gray-800">حجم المحادثات (آخر 7 أيام)</h3>
                    <div class="h-64">
                        <canvas id="chatVolumeChart"></canvas>
                    </div>
                </div>

                <!-- Response Time Chart -->
                <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl border border-gray-100">
                    <h3 class="mb-6 text-xl font-bold text-gray-800">حالة المحادثات</h3>
                    <div class="h-64">
                        <canvas id="chatStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="overflow-hidden p-6 bg-white shadow-lg rounded-xl border border-gray-100">
                <h3 class="mb-6 text-xl font-bold text-gray-800">النشاطات الأخيرة</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase bg-gray-50">المستخدم</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase bg-gray-50">الموظف</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase bg-gray-50">اخر رسالة</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase bg-gray-50">الحالة</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase bg-gray-50">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentChats as $chat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chat->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chat->agent->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chat->last_message->content ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $chat->status === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($chat->status === 'ended' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $chat->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $chat->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chat Volume Chart
            const chatVolumeCtx = document.getElementById('chatVolumeChart').getContext('2d');
            new Chart(chatVolumeCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($lastSevenDays) !!},
                    datasets: [{
                        label: 'محادثات نشطة',
                        data: {!! json_encode($activeChatsData) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'محادثات منتهية',
                        data: {!! json_encode($endedChatsData) !!},
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Chat Status Chart
            const chatStatusCtx = document.getElementById('chatStatusChart').getContext('2d');
            new Chart(chatStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['نشط', 'منتهي', 'معلق'],
                    datasets: [{
                        data: {!! json_encode($chatStatusData) !!},
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(239, 68, 68)',
                            'rgb(245, 158, 11)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
