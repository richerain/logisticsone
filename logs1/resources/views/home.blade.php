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
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.js"></script>
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 20px;
        }
        
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        /* Active sidebar link styles */
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            font-weight: 600;
        }
        
        .sidebar-link.active-submodule {
            background-color: rgba(255, 255, 255, 0.25);
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header Component -->
    @include('components.header')

    <div class="flex w-full h-[calc(100vh-4rem)]">
        <div id="overlay" class="hidden fixed inset-0 bg-black opacity-50 z-40"></div> 

        <!-- Sidebar Component -->
        @include('components.sidebar')

        <main id="main-content" class="flex-1 flex flex-col min-h-0 overflow-hidden">
            <!-- Content will be loaded dynamically here -->
            <div id="module-content" class="flex-1 overflow-y-auto p-6">
                <!-- Default dashboard content will be loaded here initially -->
                @if(request()->is('home') || request()->is('/'))
                    @include('dashboard.index')
                @endif
            </div>

            <!-- Footer -->
            <footer class="shrink-0 py-3 text-xs text-gray-500 text-center bg-white border-t border-gray-200">
            Copyright © 2025 BSIT 4117 Microfinancial I Logistic I . All Rights Reserved.
            </footer>
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
            
            // Set dashboard as active by default
            setActiveSidebarLink('dashboard');
        });

        // Function to set active sidebar link
        function setActiveSidebarLink(module) {
            // Remove active classes from all sidebar links
            const allLinks = document.querySelectorAll('.sidebar-link');
            allLinks.forEach(link => {
                link.classList.remove('active', 'active-submodule');
            });
            
            // Add active class to the clicked module
            const activeLink = document.querySelector(`[data-module="${module}"]`);
            if (activeLink) {
                if (module === 'dashboard') {
                    activeLink.classList.add('active');
                } else {
                    activeLink.classList.add('active-submodule');
                }
            }
        }
    </script>
</body>
</html>