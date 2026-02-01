<!-- resources/views/components/header.blade.php -->
<header class="h-16 bg-white flex items-center justify-between px-4 sm:px-6 relative shadow-[0_2px_8px_rgba(0,0,0,0.06)] z-40">
    
    <!-- Border Cover -->
    <div class="hidden lg:block absolute left-0 top-0 h-16 w-[2px] bg-white"></div>

    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Toggle -->
        <button id="toggle-btn" 
            class="lg:hidden w-10 h-10 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition flex items-center justify-center">
            <i class='bx bx-menu text-2xl text-gray-700'></i>
        </button>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- Clock pill -->
        <span id="real-time-clock" 
            class="text-xs font-bold text-gray-700 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
            --:--:--
        </span>

        <!-- Bell -->
        <button class="w-10 h-10 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition flex items-center justify-center relative">
            <i class='bx bx-bell text-xl text-gray-700'></i>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white"></span>
        </button>

        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

        <!-- User Profile Dropdown -->
        @php
            $user = null;
            try {
                $user = Auth::guard('sws')->user() ?: Auth::guard('vendor')->user();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Header auth check failed: ' . $e->getMessage());
            }
        @endphp
        <div class="relative">
            <button id="user-menu-button" 
                class="flex items-center gap-3 focus:outline-none group rounded-xl px-2 py-2 hover:bg-gray-100 active:bg-gray-200 transition">
                <div class="w-10 h-10 rounded-full bg-white shadow group-hover:shadow-md transition-shadow overflow-hidden flex items-center justify-center border border-gray-100">
                    @if(optional($user)->picture)
                        <img src="{{ asset('images/profile-picture/' . basename($user->picture)) }}" alt="Profile" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center font-bold text-brand-primary bg-emerald-50">
                            {{ substr(optional($user)->firstname ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="hidden md:flex flex-col items-start text-left">
                    <span class="text-sm font-bold text-gray-700 group-hover:text-brand-primary transition-colors">
                        {{ optional($user)->firstname ?? 'Guest' }} {{ optional($user)->lastname ?? '' }}
                    </span>
                    <span class="text-[10px] text-gray-500 font-medium uppercase group-hover:text-brand-primary transition-colors">
                        {{ optional($user)->roles ?? 'User' }}
                    </span>
                </div>
                <i class='bx bx-chevron-down text-gray-400 group-hover:text-brand-primary transition-colors text-lg'></i>
            </button>

            <div id="user-menu-dropdown" 
                class="dropdown-panel hidden opacity-0 translate-y-2 scale-95 
                    absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-100 
                    transition-all duration-200 z-50 origin-top-right">
                <a href="#" id="profile-modal-btn" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                    <i class='bx bx-user'></i> Profile
                </a>
                <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                    <i class='bx bx-cog'></i> Settings
                </a>
                <div class="h-px bg-gray-100"></div>
                <a href="#" id="logout-btn" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                    <i class='bx bx-log-out'></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Profile Details Modal -->
<div id="profile-modal" class="hidden fixed inset-0 bg-black/50 z-[9998] flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-5xl mx-auto shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand-background-main flex items-center justify-center text-brand-primary">
                    <i class='bx bxs-user-detail text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">User Profile</h3>
                    <p class="text-sm text-gray-500">Manage and view your account information</p>
                </div>
            </div>
            <button id="edit-profile-btn" class="px-4 py-2 bg-brand-primary hover:bg-brand-primary-hover text-white rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <i class='bx bxs-edit-alt'></i> Edit Profile
            </button>
        </div>

        <div class="p-6 bg-gray-50 overflow-y-auto custom-scrollbar">
            @if(Auth::guard('vendor')->check())
                <!-- Vendor Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column: Identity -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col items-center text-center border border-gray-100">
                            <div class="relative mb-4 group">
                                <div class="w-32 h-32 rounded-full p-1 border-2 border-dashed border-brand-primary">
                                    <img src="{{ optional($user)->picture ? asset('images/profile-picture/' . basename($user->picture)) : asset('images/default.jpg') }}" 
                                         alt="Profile Picture" 
                                         class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                </div>
                                <div class="absolute bottom-2 right-2 w-8 h-8 bg-brand-primary text-white rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                    <i class='bx bxs-badge-check'></i>
                                </div>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ optional($user)->firstname }} {{ optional($user)->lastname }}
                            </h2>
                            <span class="mt-1 px-3 py-1 rounded-full text-xs font-semibold bg-brand-background-main text-brand-primary border border-brand-border uppercase tracking-wide">
                                Vendor Account
                            </span>
                            
                            <div class="mt-6 w-full grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <span class="block text-xs text-gray-500 mb-1">Status</span>
                                    <span class="font-semibold text-gray-800 {{ optional($user)->status === 'active' ? 'text-green-600' : 'text-gray-800' }}">
                                        {{ ucfirst(optional($user)->status) }}
                                    </span>
                                </div>
                                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <span class="block text-xs text-gray-500 mb-1">Verified</span>
                                    <span class="font-semibold {{ optional($user)->email_verified_at ? 'text-brand-primary' : 'text-orange-500' }}">
                                        {{ optional($user)->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats / Dates -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class='bx bx-time-five text-brand-primary'></i> Activity
                            </h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Member Since</span>
                                    <span class="font-medium text-gray-900">{{ optional(optional($user)->created_at)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Last Updated</span>
                                    <span class="font-medium text-gray-900">{{ optional(optional($user)->updated_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Details -->
                    <div class="lg:col-span-8 space-y-6">
                        <!-- Business Details -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                    <i class='bx bxs-store text-brand-primary'></i> Business Information
                                </h4>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Vendor ID</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">{{ $user->vendorid ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Company Name</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">{{ $user->company_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Company Type</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">{{ $user->company_type ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Rating</label>
                                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 h-[38px]">
                                        @if(isset($user->rating))
                                            <div class="flex text-yellow-400 text-sm gap-0.5">
                                                @for($i=1; $i<=5; $i++)
                                                    <i class='bx {{ $i <= $user->rating ? "bxs-star" : "bx-star" }}'></i>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">{{ $user->rating }}/5</span>
                                        @else
                                            <span class="text-gray-400 text-sm">No rating yet</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal & Contact -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                    <i class='bx bxs-contact text-brand-primary'></i> Contact Details
                                </h4>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Email Address</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <i class='bx bx-envelope text-gray-400'></i> {{ optional($user)->email }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Phone Number</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <i class='bx bx-phone text-gray-400'></i> {{ optional($user)->contactnum ?: 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Age & Sex</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                        {{ optional($user)->age ?? 'N/A' }} years • <span class="capitalize">{{ optional($user)->sex ?? 'N/A' }}</span>
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Address</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 flex items-start gap-2">
                                        <i class='bx bx-map text-gray-400 mt-1'></i> 
                                        <span>{{ optional($user)->address ?: 'No address provided' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Regular User Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column: Identity -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col items-center text-center border border-gray-100">
                            <div class="relative mb-4 group">
                                <div class="w-32 h-32 rounded-full p-1 border-2 border-dashed border-brand-primary">
                                    <img src="{{ optional($user)->picture ? asset('images/profile-picture/' . basename($user->picture)) : asset('images/default.jpg') }}" 
                                         alt="Profile Picture" 
                                         class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                </div>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ optional($user)->firstname }} {{ optional($user)->lastname }}
                            </h2>
                            <span class="mt-1 px-3 py-1 rounded-full text-xs font-semibold bg-brand-background-main text-brand-primary border border-brand-border uppercase tracking-wide">
                                {{ optional($user)->roles ?? 'User' }}
                            </span>
                            
                            <div class="mt-6 w-full grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <span class="block text-xs text-gray-500 mb-1">Status</span>
                                    <span class="font-semibold text-gray-800 {{ optional($user)->status === 'active' ? 'text-green-600' : 'text-gray-800' }}">
                                        {{ ucfirst(optional($user)->status) }}
                                    </span>
                                </div>
                                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <span class="block text-xs text-gray-500 mb-1">Verified</span>
                                    <span class="font-semibold {{ optional($user)->email_verified_at ? 'text-brand-primary' : 'text-orange-500' }}">
                                        {{ optional($user)->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Stats / Dates -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class='bx bx-time-five text-brand-primary'></i> Activity
                            </h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Member Since</span>
                                    <span class="font-medium text-gray-900">{{ optional(optional($user)->created_at)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Last Updated</span>
                                    <span class="font-medium text-gray-900">{{ optional(optional($user)->updated_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Details -->
                    <div class="lg:col-span-8 space-y-6">
                        <!-- Personal Info -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                    <i class='bx bxs-id-card text-brand-primary'></i> Personal Information
                                </h4>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Employee ID</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">{{ $user->employeeid ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Full Name</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                        {{ optional($user)->firstname }} {{ optional($user)->middlename }} {{ optional($user)->lastname }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Birthdate</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                        {{ optional(optional($user)->birthdate)->format('M d, Y') ?: 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Age & Sex</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                        {{ optional($user)->age ?? 'N/A' }} years • <span class="capitalize">{{ optional($user)->sex ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                    <i class='bx bxs-contact text-brand-primary'></i> Contact Details
                                </h4>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Email Address</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <i class='bx bx-envelope text-gray-400'></i> {{ optional($user)->email }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Phone Number</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <i class='bx bx-phone text-gray-400'></i> {{ optional($user)->contactnum ?: 'N/A' }}
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Address</label>
                                    <p class="text-gray-900 font-medium bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 flex items-start gap-2">
                                        <i class='bx bx-map text-gray-400 mt-1'></i> 
                                        <span>{{ optional($user)->address ?: 'No address provided' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="p-4 border-t border-gray-100 bg-white flex justify-end">
            <button id="close-profile-modal-bottom" class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-3xl mx-auto shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand-background-main flex items-center justify-center text-brand-primary">
                    <i class='bx bxs-edit-alt text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Edit Profile</h3>
                    <p class="text-sm text-gray-500">Update your account information</p>
                </div>
            </div>
            <button id="close-edit-profile-modal" class="w-10 h-10 rounded-xl hover:bg-gray-100 flex items-center justify-center text-gray-500 hover:text-gray-700 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>

        <form id="edit-profile-form" class="flex flex-col flex-1 overflow-hidden" enctype="multipart/form-data">
            @csrf
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-8 flex-1 bg-gray-50">
                <!-- Profile Picture Section -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class='bx bxs-image text-brand-primary'></i> Profile Picture
                    </h4>
                    <div class="flex flex-col sm:flex-row gap-6 items-center">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full p-1 border-2 border-dashed border-brand-primary">
                                <img src="{{ optional($user)->picture ? asset('images/profile-picture/' . basename($user->picture)) : asset('images/default.jpg') }}" 
                                     alt="Profile Picture" 
                                     id="edit-profile-preview"
                                     class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                            </div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 bg-brand-primary text-white rounded-full flex items-center justify-center border-2 border-white shadow-sm cursor-pointer hover:bg-brand-primary-hover transition" onclick="document.getElementById('profile-upload').click()">
                                <i class='bx bxs-camera'></i>
                            </div>
                        </div>
                        <div class="flex-1 w-full sm:w-auto">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload New Picture</label>
                            <input type="file" 
                                   id="profile-upload"
                                   name="picture" 
                                   accept="image/*" 
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-xl file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-brand-background-main file:text-brand-primary
                                          hover:file:bg-brand-border
                                          cursor-pointer border border-gray-200 rounded-xl bg-white" 
                                   onchange="document.getElementById('edit-profile-preview').src = window.URL.createObjectURL(this.files[0])" />
                            <p class="mt-2 text-xs text-gray-400 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i> Allowed formats: JPG, PNG, GIF (Max: 2MB)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class='bx bxs-user-detail text-brand-primary'></i> Personal Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">First Name</label>
                            <input type="text" name="firstname" value="{{ optional($user)->firstname }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" required />
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Name</label>
                            <input type="text" name="lastname" value="{{ optional($user)->lastname }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" required />
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Middle Name</label>
                            <input type="text" name="middlename" value="{{ optional($user)->middlename }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" />
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date of Birth</label>
                            <input type="date" name="birthdate" value="{{ optional($user)->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '' }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" />
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sex</label>
                            <select name="sex" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800 bg-white">
                                <option value="" disabled {{ !optional($user)->sex ? 'selected' : '' }}>Select Sex</option>
                                <option value="male" {{ optional($user)->sex === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ optional($user)->sex === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Age</label>
                            <input type="number" name="age" value="{{ optional($user)->age }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" min="0" />
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class='bx bxs-contact text-brand-primary'></i> Contact Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" value="{{ optional($user)->email }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" required />
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone Number</label>
                            <input type="tel" name="contactnum" value="{{ optional($user)->contactnum }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" />
                        </div>
                        <div class="md:col-span-2 form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Address</label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800 resize-none">{{ optional($user)->address }}</textarea>
                        </div>
                    </div>
                </div>

                @if(Auth::guard('vendor')->check())
                <!-- Vendor Specific Info -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class='bx bxs-store text-brand-primary'></i> Company Details
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Company Name</label>
                            <input type="text" name="company_name" value="{{ $user->company_name ?? '' }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" />
                        </div>
                        <div class="form-control space-y-1.5">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Company Type</label>
                            <input type="text" name="company_type" value="{{ $user->company_type ?? '' }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-primary focus:ring-2 focus:ring-brand-background-main outline-none transition font-medium text-gray-800" />
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer Actions -->
            <div class="p-6 border-t border-gray-100 bg-white flex justify-end gap-3 sticky bottom-0 z-10">
                <button type="button" id="cancel-edit-profile" class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold hover:bg-brand-primary-hover shadow-lg shadow-brand-primary/20 transition flex items-center gap-2">
                    <i class='bx bxs-save'></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[10000] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-auto overflow-hidden transform transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class='bx bx-log-out text-2xl text-red-600'></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Sign out?</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">Are you sure you want to sign out of your account?</p>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button id="confirm-logout-btn" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Sign out
            </button>
            <button id="cancel-logout-btn" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Check for Profile Update Success Flag ---
        if (localStorage.getItem('profileUpdated')) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: 'Successfully updated Profile!'
            });

            localStorage.removeItem('profileUpdated');
        }

        // --- Sidebar Overlay Logic ---
        const toggleBtn = document.getElementById('toggle-btn');
        const overlay = document.getElementById('overlay');
        const sidebar = document.getElementById('sidebar'); // From sidebar.blade.php

        if (toggleBtn && overlay) {
            toggleBtn.addEventListener('click', () => {
                overlay.classList.toggle('hidden');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                overlay.classList.add('hidden');
                if (sidebar) sidebar.classList.add('-ml-72'); // Ensure sidebar closes
            });
        }

        // --- Clock Logic ---
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            const clockElement = document.getElementById('real-time-clock');
            if(clockElement) {
                clockElement.textContent = timeString;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- User Dropdown Logic ---
        const userMenuBtn = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        
        if (userMenuBtn && userMenuDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = userMenuDropdown.classList.contains('hidden');
                
                if (isHidden) {
                    userMenuDropdown.classList.remove('hidden');
                    setTimeout(() => {
                        userMenuDropdown.classList.remove('opacity-0', 'translate-y-2', 'scale-95');
                    }, 10);
                } else {
                    userMenuDropdown.classList.add('opacity-0', 'translate-y-2', 'scale-95');
                    setTimeout(() => {
                        userMenuDropdown.classList.add('hidden');
                    }, 200);
                }
            });

            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                    userMenuDropdown.classList.add('opacity-0', 'translate-y-2', 'scale-95');
                    setTimeout(() => {
                        userMenuDropdown.classList.add('hidden');
                    }, 200);
                }
            });
        }

        // --- Modals Logic ---
        const profileModal = document.getElementById('profile-modal');
        const editProfileModal = document.getElementById('edit-profile-modal');
        const logoutModal = document.getElementById('logout-modal');

        // Open Profile
        const profileBtn = document.getElementById('profile-modal-btn');
        if (profileBtn) {
            profileBtn.addEventListener('click', (e) => {
                e.preventDefault();
                profileModal.classList.remove('hidden');
                if(userMenuDropdown) userMenuDropdown.classList.add('hidden');
            });
        }

        // Close Profile
        const closeProfileBtn = document.getElementById('close-profile-modal-bottom');
        if (closeProfileBtn) {
            closeProfileBtn.addEventListener('click', () => {
                profileModal.classList.add('hidden');
            });
        }

        // Open Edit Profile (from Profile Modal)
        const editProfileBtn = document.getElementById('edit-profile-btn');
        if (editProfileBtn) {
            editProfileBtn.addEventListener('click', () => {
                profileModal.classList.add('hidden');
                editProfileModal.classList.remove('hidden');
            });
        }

        // Close Edit Profile
        const closeEditProfileBtn = document.getElementById('close-edit-profile-modal');
        const cancelEditProfileBtn = document.getElementById('cancel-edit-profile');
        const closeEditProfile = () => {
            editProfileModal.classList.add('hidden');
            profileModal.classList.remove('hidden'); 
        };
        if (closeEditProfileBtn) closeEditProfileBtn.addEventListener('click', closeEditProfile);
        if (cancelEditProfileBtn) cancelEditProfileBtn.addEventListener('click', closeEditProfile);

        // Edit Profile Form Submit
        const editProfileForm = document.getElementById('edit-profile-form');
        if (editProfileForm) {
            editProfileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('{{ Auth::guard("vendor")->check() ? route("vendor.profile.update") : route("profile.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modals immediately
                        if(editProfileModal) editProfileModal.classList.add('hidden');
                        if(profileModal) profileModal.classList.add('hidden');

                        // Set flag and reload
                        localStorage.setItem('profileUpdated', 'true');
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to update profile',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating profile',
                    });
                });
            });
        }

        // Logout Logic
        const logoutBtn = document.getElementById('logout-btn');
        const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
        const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                logoutModal.classList.remove('hidden');
                if(userMenuDropdown) userMenuDropdown.classList.add('hidden');
            });
        }

        if (cancelLogoutBtn) {
            cancelLogoutBtn.addEventListener('click', () => {
                logoutModal.classList.add('hidden');
            });
        }

        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', () => {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                         window.location.href = '{{ Auth::guard("vendor")->check() ? route("vendor.splash.logout") : route("splash.logout") }}';
                    } else {
                        window.location.href = '/';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.href = '/'; 
                });
            });
        }
    });
</script>
