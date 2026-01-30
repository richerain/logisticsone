<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}" />
    <title>Logistics I</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#059669',
                            'primary-hover': '#047857',
                            'background-main': '#F0FDF4',
                            border: '#D1FAE5',
                            'text-primary': '#1F2937',
                            'text-secondary': '#4B5563',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.js"></script>
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Inject JWT Token from server session if available
        window.SERVER_JWT_TOKEN = '{{ $jwtToken ?? "" }}';
        if (window.SERVER_JWT_TOKEN) {
            localStorage.setItem('jwt', window.SERVER_JWT_TOKEN);
        }
    </script>
    <style>
        /* Custom scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }
    </style>
</head>
<body class="bg-gray-100 overflow-hidden">
    <!-- Sidebar Component (Fixed) -->
    @include('components.sidebar')

    <!-- Main Content Wrapper -->
    <div class="lg:ml-72 h-screen flex flex-col transition-all duration-300">
        <!-- Header Component -->
        @include('components.header')

        <!-- Main Content -->
        <main id="main-content" class="flex-1 flex flex-col min-h-0 overflow-hidden relative">
            <!-- Content will be loaded dynamically here -->
            <div id="module-content" class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                <!-- Default dashboard content will be loaded here initially -->
                @if(request()->is('home') || request()->is('/'))
                    @include('dashboard.index')
                @endif
            </div>

        </main>
    </div>

    <!-- Session Timeout Modal -->
    @include('components.session-timeout-modal')

    <script>
        // Global function to initialize charts
        function initializeCharts() {
            // Bar (static)
            const ctx1 = document.getElementById('chart1');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: ['Market A', 'Market B', 'Market C'],
                        datasets: [{
                            label: 'Market Share',
                            data: [34, 28, 22],
                            backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, max: 40 } }
                    }
                });
            }

            // Doughnut (static)
            const ctx2 = document.getElementById('chart2');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Equipment', 'Document', 'Supplies'],
                        datasets: [{
                            data: [1400, 2000, 1200],
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            // Line (static)
            const ctx3 = document.getElementById('chart3');
            if (ctx3) {
                new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: ['2025', '2030', '2035'],
                        datasets: [{
                            label: 'Vendors Market Value (₱)',
                            data: [24.25, 50, 103.4],
                            borderColor: '#36A2EB',
                            backgroundColor: 'rgba(54,162,235,0.15)',
                            tension: 0.2,
                            fill: true,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        }

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });
    </script>
</body>
</html>