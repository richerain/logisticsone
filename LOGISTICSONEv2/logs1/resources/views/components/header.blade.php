<!-- resources/views/components/header.blade.php -->
<nav class="w-full p-3 h-16 bg-[#28644c] text-white shadow-md">
    <div class="flex justify-between items-center h-full">
        <div class="flex items-center space-x-4">
            <!-- Sidebar toggle button -->
            <button id="toggle-btn" class="pl-2 focus:outline-none"><i class="bx bx-menu text-2xl cursor-pointer"></i></button>
            <!-- company name & logo button -->
            <button type="button" onclick="window.location.href='/home'" class="flex items-center pl-2 focus:outline-none">
                <h1 class="text-2xl font-bold tracking-tight">Microfinancial</h1>
            </button>
        </div>
        <!-- profile section -->
        <div class="relative dropdown dropdown-end" id="profile-dropdown">
            <button id="profile-btn" type="button" class="flex items-center btn m-1 bg-transparent border-none hover:bg-white/10 focus:outline-none" aria-expanded="false" aria-controls="profile-menu">
                <img src="{{ asset('images/default.jpg') }}" alt="default-profile" class="h-10 w-10 rounded-full object-cover" loading="lazy" />
                <div class="text-left">
                    <div class="text-sm font-medium text-white">Firstname</div>
                    <div class="text-xs text-white/80">Role</div>
                </div>
                <i id="profile-chevron" class='bx bx-chevron-down text-white transition-transform duration-200'></i>
            </button>

            <ul id="profile-menu" class="hidden text-gray-700 dropdown-content menu bg-white rounded-box z-50 w-52 p-2 shadow-sm mt-2 right-0">
                <li><a class="flex items-center gap-2"><i class='bx bxs-user'></i>Profile</a></li>
                <li><a class="flex items-center gap-2"><i class='bx bxs-log-out'></i>Logout</a></li>
            </ul>

            <script>
                // profile dropdown toggle start
                (function () {
                    const btn = document.getElementById('profile-btn');
                    const menu = document.getElementById('profile-menu');
                    const chevron = document.getElementById('profile-chevron');

                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const isOpen = !menu.classList.contains('hidden');
                        if (isOpen) {
                            menu.classList.add('hidden');
                            chevron.classList.remove('rotate-180');
                            btn.setAttribute('aria-expanded', 'false');
                        } else {
                            menu.classList.remove('hidden');
                            chevron.classList.add('rotate-180');
                            btn.setAttribute('aria-expanded', 'true');
                        }
                    });

                    // close when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!btn.contains(e.target) && !menu.contains(e.target)) {
                            if (!menu.classList.contains('hidden')) {
                                menu.classList.add('hidden');
                                chevron.classList.remove('rotate-180');
                                btn.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                })();
                // profile dropdown toggle end
            </script>
        </div>
    </div>
</nav>