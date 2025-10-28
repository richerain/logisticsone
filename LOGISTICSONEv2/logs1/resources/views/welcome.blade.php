<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="w-full p-3 h-16 bg-[#28644c] text-white shadow-md">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center space-x-4">
                <button id="toggle-btn" class="pl-2 focus:outline-none"><i class="bx bx-menu text-2xl cursor-pointer"></i></button>
                <h1 class="text-2xl font-bold tracking-tight">Microfinancial</h1>
            </div>
            <div class="flex items-center space-x-1">
                <div class="flex items-center space-x-2 cursor-pointer px-3 py-2 transition duration-200">
                    <i class="bx bx-user text-[18px] bg-white text-[#28644c] px-2.5 py-2 rounded-full"></i>
                    <span class="text-white font-medium">Ryujin</span>
                    <i class="bx bx-chevron-down text-sm"></i>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex w-full">
        <div id="overlay" class="hidden fixed inset-0 bg-black opacity-50 z-40"></div> 

        <aside id="sidebar" class="bg-[#2f855A] text-white flex flex-col z-50 absolute md:relative w-72 -ml-72 md:ml-0 transition-all duration-300 ease-in-out h-auto">
            <div class="department-header text-center py-5 mx-2 border-b border-white/50">
                <h1 class="text-xl font-bold">Logistics I</h1>
            </div>
            <div class="px-3 py-5 flex-1">
                <ul class="space-y-1">
                    <!-- dashboard sidebar btn start -->
                    <li>
                        <a href="#" class="flex items-center font-medium text-md hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-word">
                            <i class="bx bxs-dashboard mr-2 shrink-0"></i>
                            <span class="flex-1">Dashboard</span>
                        </a>
                    </li>
                    <!-- dashboard sidebar btn end -->
                    <!-- Procurement & Sourcing Management btn start -->
                    <li class="has-dropdown">
                        <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                            <div class="flex items-center flex-1 min-w-0">
                                <i class="bx bxs-cart mr-2 shrink-0"></i>
                                <span class="module-text flex-1">Procurement & Sourcing Management</span>
                            </div>
                            <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                        </div>
                        <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text">Purchase Management</span></a></li>
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text">Vendor Quote</span></a></li>
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text">Vendor Management</span></a></li>
                        </ul>
                    </li>
                    <!-- Procurement & Sourcing Management btn end -->
                    <!-- Smart Warehousing System btn start -->
                    <li class="has-dropdown">
                        <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                            <div class="flex items-center flex-1 min-w-0">
                                <i class="bx bxs-store mr-2 shrink-0"></i>
                                <span class="flex-1">Smart Warehousing System</span>
                            </div>
                            <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                        </div>
                        <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text">Goods Received</span></a></li>
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text">Digital Inventory</span></a></li>
                        </ul>
                    </li>
                    <!-- Smart Warehousing System btn end -->
                    <!-- Project Logistics Tracker btn start -->
                    <li class="has-dropdown">
                        <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                            <div class="flex items-center flex-1 min-w-0">
                                <i class="bx bxs-truck mr-2 shrink-0"></i>
                                <span class="flex-1">Project Logistics Tracker</span>
                            </div>
                            <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                        </div>
                        <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words">Logistics Projects</a></li>
                        </ul>
                    </li>
                    <!-- Project Logistics Tracker btn end -->
                    <!-- Asset Lifecycle & Maintenance btn start -->
                    <li class="has-dropdown">
                        <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                            <div class="flex items-center flex-1 min-w-0">
                                <i class="bx bxs-hard-hat mr-2 shrink-0"></i>
                                <span class="flex-1">Asset Lifecycle & Maintenance</span>
                            </div>
                            <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                        </div>
                        <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words">Asset Management</a></li>
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words">Maintenance Management</a></li>
                        </ul>
                    </li>
                    <!-- Asset Lifecycle & Maintenance btn end -->
                    <!-- Document Tracking & Logistics Record btn start -->
                    <li class="has-dropdown">
                        <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                            <div class="flex items-center flex-1 min-w-0">
                                <i class="bx bxs-file mr-2 shrink-0"></i>
                                <span class="flex-1">Document Tracking & Logistics Record</span>
                            </div>
                            <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                        </div>
                        <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words">Document Tracker</a></li>
                            <li><a href="#" class="flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words">Logistics Record</a></li>
                        </ul>
                    </li>
                    <!-- Document Tracking & Logistics Record btn end -->
                </ul>
            </div>
        </aside>

        <main id="main-content" class="flex-1 p-6 min-h-screen w-full">
            <!--  All Main Content Ditooooooo dyan kayo mag lalagay ng mga contentsss nyooooooooo      -->
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("main-content");
            const toggleBtn = document.getElementById("toggle-btn");
            const overlay = document.getElementById("overlay");
            const dropdownToggles = document.querySelectorAll(".has-dropdown > div");

            function toggleSidebar() {
                if (window.innerWidth >= 768) {
                    if (sidebar.classList.contains("md:-ml-72")) {
                        sidebar.classList.remove("md:-ml-72");
                    } else {
                        sidebar.classList.add("md:-ml-72");
                        mainContent.classList.remove("md:ml-72");
                    }
                } else {
                    sidebar.classList.toggle("ml-0");
                    overlay.classList.toggle("hidden");
                    if (sidebar.classList.contains("ml-0")) {
                        document.body.style.overflow = "hidden";
                    } else {
                        document.body.style.overflow = "";
                    }
                }
            }

            function closeAllDropdowns() {
                dropdownToggles.forEach((toggle) => {
                    const dropdown = toggle.nextElementSibling;
                    const chevron = toggle.querySelector(".bx-chevron-down");
                    if (!dropdown.classList.contains("hidden")) {
                        dropdown.classList.add("hidden");
                        chevron.classList.remove("rotate-180");
                    }
                });
            }

            dropdownToggles.forEach((toggle) => {
                toggle.addEventListener("click", () => {
                    const dropdown = toggle.nextElementSibling;
                    const chevron = toggle.querySelector(".bx-chevron-down");
                    dropdownToggles.forEach((otherToggle) => {
                        if (otherToggle !== toggle) {
                            otherToggle.nextElementSibling.classList.add("hidden");
                            otherToggle.querySelector(".bx-chevron-down").classList.remove("rotate-180");
                        }
                    });
                    dropdown.classList.toggle("hidden");
                    chevron.classList.toggle("rotate-180");
                });
            });

            overlay.addEventListener("click", () => {
                sidebar.classList.remove("ml-0");
                overlay.classList.add("hidden");
                document.body.style.overflow = "";
            });

            toggleBtn.addEventListener("click", toggleSidebar);

            window.addEventListener("resize", () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove("ml-0");
                    overlay.classList.add("hidden");
                    document.body.style.overflow = "";
                    if (!sidebar.classList.contains("md:-ml-72") && !mainContent.classList.contains("md:ml-72")) {
                        sidebar.classList.add("md:-ml-72");
                    }
                } else {
                    sidebar.classList.remove("md:-ml-72");
                    mainContent.classList.remove("md:ml-72");
                }
                closeAllDropdowns();
            });
        });
    </script>
</body>
</html>