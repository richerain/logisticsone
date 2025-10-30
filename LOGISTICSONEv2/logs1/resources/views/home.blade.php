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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Header Component -->
    @include('components.header')

    <div class="flex w-full">
        <div id="overlay" class="hidden fixed inset-0 bg-black opacity-50 z-40"></div> 

        <!-- Sidebar Component -->
        @include('components.sidebar')

        <main id="main-content" class="flex-1 flex flex-col p-6 min-h-[calc(100vh-4rem)] w-full">
            <!-- Content will be loaded dynamically here -->
            <div id="module-content">
                <!-- Default dashboard content will be loaded here initially -->
                @if(request()->is('home') || request()->is('/'))
                    @include('dashboard.index')
                @endif
            </div>

            <!-- Footer -->
            <footer class="mt-auto pt-5 text-xs text-gray-500 text-center">
            Copyright © 2025 BSIT 4117 Microfinancial I Logistic I . All Rights Reserved.
            </footer>
        </main>
    </div>

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