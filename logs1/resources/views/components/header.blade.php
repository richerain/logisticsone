<!-- resources/views/components/header.blade.php -->
<nav class="w-full p-3 h-16 bg-[#28644c] text-white shadow-md relative z-40">
    <div class="flex justify-between items-center h-full">
        <div class="flex items-center space-x-4">
            <!-- Sidebar toggle button -->
            <button id="toggle-btn" class="pl-2 focus:outline-none"><i class="bx bx-menu text-2xl cursor-pointer"></i></button>
            <!-- company name & logo button -->
            <button type="button" onclick="window.location.href='{{ Auth::guard('vendor')->check() ? '/vendor/home' : '/home' }}'" class="flex items-center pl-2 focus:outline-none">
                <h1 class="text-2xl font-bold tracking-tight">Microfinancial</h1>
            </button>
        </div>
        <!-- profile section -->
        <div class="relative dropdown dropdown-end" id="profile-dropdown">
            @php($user = Auth::guard('sws')->user() ?: Auth::guard('vendor')->user())
            <button id="profile-btn" type="button" class="flex items-center btn m-1 bg-transparent border-none hover:bg-white/10 focus:outline-none" aria-expanded="false" aria-controls="profile-menu">
                <img src="{{ optional($user)->picture ? asset(optional($user)->picture) : asset('images/default.jpg') }}" alt="profile" class="h-10 w-10 rounded-full object-cover" loading="lazy" />
                <div class="text-left ml-2">
                    <div class="text-sm font-medium text-white">{{ optional($user)->firstname ?? 'Firstname' }}</div>
                    <div class="text-xs text-white/80 capitalize">{{ optional($user)->roles ?? 'Role' }}</div>
                </div>
                <i id="profile-chevron" class='bx bx-chevron-down text-white transition-transform duration-200 ml-2'></i>
            </button>

            <ul id="profile-menu" class="hidden text-gray-700 dropdown-content menu bg-white rounded-box z-50 w-52 p-2 shadow-sm mt-2 right-0">
                <li><a href="#" id="profile-modal-btn" class="flex items-center gap-2 hover:bg-gray-100 rounded-lg"><i class='bx bxs-user'></i>Profile</a></li>
                <li><a href="#" id="logout-btn" class="flex items-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg"><i class='bx bxs-log-out'></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Profile Details Modal -->
<div id="profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9998] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-5xl mx-auto">
        <!-- Modal Header - More Compact -->
        <div class="flex bg-green-700 items-center rounded-t-lg justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i class='bx bxs-user text-green-600 text-lg'></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-100">User Profile</h3>
                    <p class="text-xs text-gray-200">View your account details</p>
                </div>
            </div>
            <button id="edit-profile-btn" class="btn btn-sm bg-white text-green-700 border-none hover:bg-gray-100">
                Edit Profile
            </button>
        </div>

        <div class="p-5 bg-green-100">
            @if(Auth::guard('vendor')->check())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col items-center text-center">
                        <div class="relative mb-3">
                            <img src="{{ optional($user)->picture ? asset(optional($user)->picture) : asset('images/default.jpg') }}" 
                                 alt="Profile Picture" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-green-200 shadow">
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 leading-tight">
                            {{ optional($user)->firstname }} {{ optional($user)->lastname }}
                        </h2>
                        <p class="text-green-600 text-xs font-medium capitalize mt-1">Vendor Account</p>
                        <div class="mt-3 w-full space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-400 text-green-800 capitalize">
                                    {{ optional($user)->status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Verified</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ optional($user)->email_verified_at ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ optional($user)->email_verified_at ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Member Since</span>
                                <span class="text-gray-800">{{ optional(optional($user)->created_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="text-gray-800">{{ optional(optional($user)->updated_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h4 class="font-semibold text-gray-800 text-sm flex items-center">
                                <i class='bx bxs-store mr-1 text-green-600 text-sm'></i>
                                Vendor Details
                            </h4>
                            <span class="text-[11px] uppercase tracking-wide text-gray-400">Account</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vendor ID</span>
                                <span class="text-gray-800 font-semibold">{{ $user->vendorid ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sex</span>
                                <span class="text-gray-800 capitalize">{{ optional($user)->sex ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Age</span>
                                <span class="text-gray-800">{{ optional($user)->age ?: 'N/A' }}</span>
                            </div>
                            @if(isset($user->company_type))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Company Type</span>
                                <span class="text-gray-800">{{ $user->company_type }}</span>
                            </div>
                            @endif
                            @if(isset($user->company_name))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Company Name</span>
                                <span class="text-gray-800">{{ $user->company_name }}</span>
                            </div>
                            @endif
                            @if(isset($user->rating))
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Rating</span>
                                <div class="flex items-center text-yellow-500">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $user->rating)
                                            <i class='bx bxs-star text-xs'></i>
                                        @else
                                            <i class='bx bx-star text-xs'></i>
                                        @endif
                                    @endfor
                                    <span class="text-gray-600 ml-1 text-xs">({{ $user->rating }}/5)</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h4 class="font-semibold text-gray-800 text-sm flex items-center">
                                <i class='bx bxs-contact mr-1 text-green-600 text-sm'></i>
                                Contact & Address
                            </h4>
                            <span class="text-[11px] uppercase tracking-wide text-gray-400">Info</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="text-gray-600 block">Email</span>
                                <span class="text-gray-800 break-all">{{ optional($user)->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 block">Phone</span>
                                <span class="text-gray-800">{{ optional($user)->contactnum ?: 'N/A' }}</span>
                            </div>
                            <div class="pt-1">
                                <span class="text-gray-600 block mb-1 flex items-center">
                                    <i class='bx bxs-map mr-1 text-green-600 text-sm'></i>
                                    Address
                                </span>
                                <p class="text-gray-800 text-xs mt-1">
                                    @if(optional($user)->address)
                                        {{ optional($user)->address }}
                                    @else
                                        <span class="text-gray-500 italic">No address provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col items-center text-center">
                        <div class="relative mb-3">
                            <img src="{{ optional($user)->picture ? asset(optional($user)->picture) : asset('images/default.jpg') }}" 
                                 alt="Profile Picture" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-green-200 shadow">
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 leading-tight">
                            {{ optional($user)->firstname }} {{ optional($user)->lastname }}
                        </h2>
                        <p class="text-green-600 text-xs font-medium capitalize mt-1">
                            {{ optional($user)->roles }}
                        </p>
                        <div class="mt-3 w-full space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-400 text-green-800 capitalize">
                                    {{ optional($user)->status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Verified</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ optional($user)->email_verified_at ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ optional($user)->email_verified_at ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Member Since</span>
                                <span class="text-gray-800">{{ optional(optional($user)->created_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="text-gray-800">{{ optional(optional($user)->updated_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h4 class="font-semibold text-gray-800 text-sm flex items-center">
                                <i class='bx bxs-id-card mr-1 text-green-600 text-sm'></i>
                                Personal Info
                            </h4>
                            <span class="text-[11px] uppercase tracking-wide text-gray-400">Account</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Employee ID</span>
                                <span class="text-gray-800 font-semibold">{{ $user->employeeid ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 block">Full Name</span>
                                <span class="text-gray-800 text-xs">
                                    {{ optional($user)->firstname }} {{ optional($user)->middlename }} {{ optional($user)->lastname }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sex</span>
                                <span class="text-gray-800 capitalize">{{ optional($user)->sex ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Age</span>
                                <span class="text-gray-800">{{ optional($user)->age ?: 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Birthdate</span>
                                <span class="text-gray-800">
                                    {{ optional(optional($user)->birthdate)->format('M d, Y') ?: 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h4 class="font-semibold text-gray-800 text-sm flex items-center">
                                <i class='bx bxs-contact mr-1 text-green-600 text-sm'></i>
                                Contact & Address
                            </h4>
                            <span class="text-[11px] uppercase tracking-wide text-gray-400">Info</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="text-gray-600 block">Email</span>
                                <span class="text-gray-800 break-all">{{ optional($user)->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 block">Phone</span>
                                <span class="text-gray-800">{{ optional($user)->contactnum ?: 'N/A' }}</span>
                            </div>
                            <div class="pt-1">
                                <span class="text-gray-600 block mb-1 flex items-center">
                                    <i class='bx bxs-map mr-1 text-green-600 text-sm'></i>
                                    Address
                                </span>
                                <p class="text-gray-800 text-xs mt-1">
                                    @if(optional($user)->address)
                                        {{ optional($user)->address }}
                                    @else
                                        <span class="text-gray-500 italic">No address provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex justify-end items-center p-3 border-t border-gray-200 bg-green-700 rounded-b-lg">
            <button id="close-profile-modal-bottom" class="btn btn-ghost text-gray-100 hover:bg-gray-100 hover:text-gray-800 btn-sm">
                Close
            </button>
        </div>
    </div>
</div>

<div id="edit-profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-3xl mx-auto flex flex-col max-h-[90vh]">
        <div class="flex items-center rounded-t-lg justify-between p-4 border-b border-gray-200 bg-green-700 flex-shrink-0">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i class='bx bxs-edit text-green-600 text-lg'></i>
                </div>
                <h3 class="text-base font-semibold text-gray-100">Edit Profile</h3>
            </div>
            <button id="close-edit-profile-modal" class="text-gray-200 hover:text-white transition-colors">
                <i class='bx bx-x text-lg'></i>
            </button>
        </div>
        <form id="edit-profile-form" class="p-4 space-y-4 overflow-y-auto flex-1" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-3 sm:space-y-0">
                    <div class="flex justify-center">
                        <img src="{{ optional($user)->picture ? asset(optional($user)->picture) : asset('images/default.jpg') }}" 
                             alt="Profile Picture" 
                             class="w-20 h-20 rounded-full object-cover border-3 border-green-200 shadow">
                    </div>
                    <div class="flex-1 space-y-2">
                        <input id="picture-input" type="file" name="picture" accept="image/*" class="file-input file-input-bordered file-input-sm w-full max-w-xs">
                        <label class="inline-flex items-center space-x-2 text-xs text-gray-700">
                            <input id="remove-picture-checkbox" type="checkbox" name="remove_picture" value="1" class="checkbox checkbox-xs">
                            <span>Remove current picture</span>
                        </label>
                    </div>
                </div>

                @if(Auth::guard('vendor')->check())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname', optional($user)->firstname) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Middle Name</label>
                            <input type="text" name="middlename" value="{{ old('middlename', optional($user)->middlename) }}" class="input input-bordered input-sm w-full">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Last Name</label>
                            <input type="text" name="lastname" value="{{ old('lastname', optional($user)->lastname) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Sex</label>
                            <select name="sex" class="select select-bordered select-sm w-full" required>
                                <option value="" @if(!optional($user)->sex) selected @endif>Not set</option>
                                <option value="male" @if(optional($user)->sex === 'male') selected @endif>Male</option>
                                <option value="female" @if(optional($user)->sex === 'female') selected @endif>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Age</label>
                            <input type="number" name="age" min="0" max="150" value="{{ old('age', optional($user)->age) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Birthdate</label>
                            <input type="date" name="birthdate" value="{{ optional(optional($user)->birthdate)->format('Y-m-d') }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', optional($user)->email) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Phone</label>
                            <input type="text" name="contactnum" value="{{ old('contactnum', optional($user)->contactnum) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-gray-700 block mb-1">Address</label>
                            <textarea name="address" rows="2" class="textarea textarea-bordered textarea-sm w-full" required>{{ old('address', optional($user)->address) }}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Company Type</label>
                            <select name="company_type" class="select select-bordered select-sm w-full" required>
                                <option value="" @if(!optional($user)->company_type) selected @endif>Select Company Type</option>
                                <option value="Equipment" @if(old('company_type', optional($user)->company_type) === 'Equipment') selected @endif>Equipment</option>
                                <option value="Supplies" @if(old('company_type', optional($user)->company_type) === 'Supplies') selected @endif>Supplies</option>
                                <option value="Furniture" @if(old('company_type', optional($user)->company_type) === 'Furniture') selected @endif>Furniture</option>
                                <option value="Automotive" @if(old('company_type', optional($user)->company_type) === 'Automotive') selected @endif>Automotive</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name', optional($user)->company_name) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname', optional($user)->firstname) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Middle Name</label>
                            <input type="text" name="middlename" value="{{ old('middlename', optional($user)->middlename) }}" class="input input-bordered input-sm w-full">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Last Name</label>
                            <input type="text" name="lastname" value="{{ old('lastname', optional($user)->lastname) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Sex</label>
                            <select name="sex" class="select select-bordered select-sm w-full" required>
                                <option value="" @if(!optional($user)->sex) selected @endif>Not set</option>
                                <option value="male" @if(optional($user)->sex === 'male') selected @endif>Male</option>
                                <option value="female" @if(optional($user)->sex === 'female') selected @endif>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Age</label>
                            <input type="number" name="age" min="0" max="150" value="{{ old('age', optional($user)->age) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Birthdate</label>
                            <input type="date" name="birthdate" value="{{ optional(optional($user)->birthdate)->format('Y-m-d') }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', optional($user)->email) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 block mb-1">Phone</label>
                            <input type="text" name="contactnum" value="{{ old('contactnum', optional($user)->contactnum) }}" class="input input-bordered input-sm w-full" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-gray-700 block mb-1">Address</label>
                            <textarea name="address" rows="2" class="textarea textarea-bordered textarea-sm w-full" required>{{ old('address', optional($user)->address) }}</textarea>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-2 border-t border-gray-200 pt-3 mt-2">
                <button type="button" id="cancel-edit-profile" class="btn btn-ghost text-gray-700 hover:bg-gray-100 btn-sm">
                    Cancel
                </button>
                <button type="submit" id="save-edit-profile" class="btn btn-success btn-sm text-white bg-green-600 hover:bg-green-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-80 mx-4">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-log-out text-red-600 text-xl'></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Confirm Logout</h3>
        </div>
        
        <p class="text-gray-600 mb-6">Are you sure you want to logout from the system?</p>
        
        <div class="flex justify-end space-x-3">
            <button id="cancel-logout" class="btn btn-ghost text-gray-600 hover:bg-gray-100">Cancel</button>
            <button id="confirm-logout" class="btn btn-error text-white bg-red-600 hover:bg-red-700">
                <span id="logout-text">Yes, Logout</span>
                <span id="logout-spinner" class="loading loading-spinner loading-sm hidden"></span>
            </button>
        </div>
    </div>
</div>

<script>
    // Profile dropdown toggle start
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

        // Close when clicking outside
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
    // Profile dropdown toggle end

    document.addEventListener('DOMContentLoaded', function() {
        const profileModalBtn = document.getElementById('profile-modal-btn');
        const profileModal = document.getElementById('profile-modal');
        const closeProfileModalBottom = document.getElementById('close-profile-modal-bottom');
        const editProfileBtn = document.getElementById('edit-profile-btn');
        const editProfileModal = document.getElementById('edit-profile-modal');
        const closeEditProfileModal = document.getElementById('close-edit-profile-modal');
        const cancelEditProfile = document.getElementById('cancel-edit-profile');
        const editProfileForm = document.getElementById('edit-profile-form');
        const saveEditProfile = document.getElementById('save-edit-profile');
        const isVendorUser = {{ Auth::guard('vendor')->check() ? 'true' : 'false' }};
        const updateProfileUrl = isVendorUser ? '{{ route('vendor.profile.update') }}' : '{{ route('profile.update') }}';

        function disableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'none';
                sidebar.style.userSelect = 'none';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'none';
                link.style.cursor = 'default';
            });
        }

        // Function to enable sidebar interactions
        function enableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'auto';
                sidebar.style.userSelect = 'auto';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'auto';
                link.style.cursor = 'pointer';
            });
        }

        function openEditProfileModal() {
            if (editProfileModal) {
                editProfileModal.classList.remove('hidden');
            }
        }

        function closeEditProfileModalFunc() {
            if (editProfileModal) {
                editProfileModal.classList.add('hidden');
            }
        }

        profileModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            profileModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            disableSidebar();
            
            // Close profile dropdown
            document.getElementById('profile-menu').classList.add('hidden');
            const menu = document.getElementById('profile-menu');
            const chevron = document.getElementById('profile-chevron');
            const btn = document.getElementById('profile-btn');
            if (menu) {
                menu.classList.add('hidden');
            }
            if (chevron) {
                chevron.classList.remove('rotate-180');
            }
            if (btn) {
                btn.setAttribute('aria-expanded', 'false');
            }
        });

        function closeProfileModalFunc() {
            profileModal.classList.add('hidden');
            document.body.style.overflow = '';
            enableSidebar();
            if (editProfileModal) {
                editProfileModal.classList.add('hidden');
            }
        }

        closeProfileModalBottom.addEventListener('click', closeProfileModalFunc);

        profileModal.addEventListener('click', function(e) {
            if (e.target === profileModal) {
                closeProfileModalFunc();
            }
        });

        if (editProfileBtn) {
            editProfileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openEditProfileModal();
            });
        }

        if (closeEditProfileModal) {
            closeEditProfileModal.addEventListener('click', function(e) {
                e.preventDefault();
                closeEditProfileModalFunc();
            });
        }

        if (cancelEditProfile) {
            cancelEditProfile.addEventListener('click', function(e) {
                e.preventDefault();
                closeEditProfileModalFunc();
            });
        }

        if (editProfileModal) {
            editProfileModal.addEventListener('click', function(e) {
                if (e.target === editProfileModal) {
                    closeEditProfileModalFunc();
                }
            });
        }

        if (editProfileForm && updateProfileUrl) {
            editProfileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(editProfileForm);
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
                if (saveEditProfile) {
                    saveEditProfile.disabled = true;
                    saveEditProfile.textContent = 'Saving...';
                }
                fetch(updateProfileUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'include'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw { status: response.status, data: data };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        if (typeof Swal !== 'undefined') {
                            closeProfileModalFunc();
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Profile updated'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    } else {
                        throw { status: 400, data: data };
                    }
                })
                .catch(err => {
                    let message = 'Failed to update profile.';
                    if (err && err.data && err.data.message) {
                        message = err.data.message;
                    }
                    if (err && err.data && err.data.errors) {
                        const errors = err.data.errors;
                        const parts = [];
                        Object.keys(errors).forEach(key => {
                            if (Array.isArray(errors[key])) {
                                parts.push(errors[key].join(' '));
                            }
                        });
                        if (parts.length) {
                            message = parts.join(' ');
                        }
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: message
                        });
                    } else {
                        alert(message);
                    }
                })
                .finally(() => {
                    if (saveEditProfile) {
                        saveEditProfile.disabled = false;
                        saveEditProfile.textContent = 'Save Changes';
                    }
                });
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (editProfileModal && !editProfileModal.classList.contains('hidden')) {
                    closeEditProfileModalFunc();
                } else if (!profileModal.classList.contains('hidden')) {
                    closeProfileModalFunc();
                }
            }
        });
    });

    // Logout functionality start
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn');
        const logoutModal = document.getElementById('logout-modal');
        const cancelLogout = document.getElementById('cancel-logout');
        const confirmLogout = document.getElementById('confirm-logout');
        const logoutText = document.getElementById('logout-text');
        const logoutSpinner = document.getElementById('logout-spinner');

        // Function to disable sidebar interactions
        function disableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'none';
                sidebar.style.userSelect = 'none';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'none';
                link.style.cursor = 'default';
            });
        }

        // Function to enable sidebar interactions
        function enableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'auto';
                sidebar.style.userSelect = 'auto';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'auto';
                link.style.cursor = 'pointer';
            });
        }

        // Open logout confirmation modal
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logoutModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            disableSidebar();
            
            // Close profile dropdown
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('profile-chevron').classList.remove('rotate-180');
            document.getElementById('profile-btn').setAttribute('aria-expanded', 'false');
        });

        // Close modal when cancel is clicked
        cancelLogout.addEventListener('click', function() {
            logoutModal.classList.add('hidden');
            document.body.style.overflow = '';
            enableSidebar();
        });

        // Close modal when clicking outside
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.classList.add('hidden');
                document.body.style.overflow = '';
                enableSidebar();
            }
        });

        // Handle logout confirmation
        confirmLogout.addEventListener('click', function() {
            // Show loading state
            logoutText.textContent = 'Logging out...';
            logoutSpinner.classList.remove('hidden');
            confirmLogout.disabled = true;

            const isVendor = {{ Auth::guard('vendor')->check() ? 'true' : 'false' }};
            const logoutUrl = isVendor ? '/api/vendor/logout' : '/api/logout';
            fetch(logoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Logout failed');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    try { localStorage.removeItem('jwt'); localStorage.removeItem('jwt_exp'); } catch (e) {}
                    window.location.href = isVendor ? '/vendor/splash-logout' : '/splash-logout';
                } else {
                    throw new Error(data.message || 'Logout failed');
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                // Reset button state
                logoutText.textContent = 'Yes, Logout';
                logoutSpinner.classList.add('hidden');
                confirmLogout.disabled = false;
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Logout Failed',
                    text: 'An error occurred during logout. Please try again.'
                });
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                logoutModal.classList.add('hidden');
                document.body.style.overflow = '';
                enableSidebar();
            }
        });
    });
    // Logout functionality end
</script>
