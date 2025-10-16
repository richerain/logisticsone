<header class="bg-[#28644c] text-white p-3 flex justify-between items-center fixed w-full z-10 top-0">
    <div class="flex items-center space-x-4">
        <button id="sidebar-toggle" class="p-2 w-8 h-8 flex items-center justify-center" title="Sidebar">
            <i class="bx bx-menu text-white text-1xl hover:text-gray-300 cursor-pointer"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="text-lg pl-3 font-bold">Microfinancial</a>
    </div>

    <div class="flex items-center space-x-4 relative">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="relative p-2 transition duration-200 focus:outline-none cursor-pointer" title="Notification" id="notification-toggle"> 
                <i class="bx bxs-bell bx-tada-hover text-lg"></i>
            </div>
            <ul tabindex="0" class="dropdown-content menu text-gray-500 bg-base-100 rounded-box z-[1] w-52 p-2 shadow-lg hidden" id="notification-dropdown">
                <li>
                    <a class="dropdown-item" onclick="openModal('messages-modal')">
                        <i class="bx bxs-message-dots"></i>Messages
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" onclick="openModal('reminder-modal')"><i class="bx bxs-calendar-check"></i>Reminder Calendar</a>
                </li>
            </ul>
        </div>
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="flex items-center space-x-2 rounded-full transition duration-200 focus:outline-none cursor-pointer" title="My Profile" id="profile-toggle">
                <div class="avatar">
                    <div class="w-8 rounded-full">
                        <img id="header-profile-picture" src="{{ asset('images/default.jpg') }}" alt="User Profile" />
                    </div>
                </div>
                <span class="text-white font-xs" id="header-username">my name</span>
                <i class="bx bx-chevron-right chevron-profile pr-3"></i>
            </div>
            <ul tabindex="0" class="dropdown-content menu text-gray-500 bg-base-100 rounded-box z-[1] w-52 p-2 shadow-lg hidden" id="profile-dropdown">
                <li><a class="dropdown-item" onclick="openModal('profile-modal')"><i class="bx bxs-user-circle"></i>Profile</a></li>
                <li><a class="dropdown-item" onclick="openModal('settings-modal')"><i class="bx bxs-cog"></i>Settings</a></li>
                <li><a class="dropdown-item" onclick="handleLogoutWithSweetAlert()"><i class="bx bx-log-out"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Modals (global) -->
<div id="underdevelop-modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Under Development</h3>
        <p class="py-4">This feature is still under development.</p>
        <div class="modal-action">
            <button class="btn" onclick="closeModal('underdevelop-modal')">Close</button>
        </div>
    </div>
</div>

<!-- Enhanced Profile Modal -->
<div id="profile-modal" class="modal">
    <div class="modal-box max-w-5xl w-11/12 p-0">
        <div class="bg-green-700 from-primary to-primary/90 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-2xl">My Profile</h3>
                <div class="flex items-center space-x-2">
                    <span id="modal-status-badge" class="badge badge-primary badge-lg">Active</span>
                    <span class="badge badge-warning badge-lg flex items-center gap-1">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        Online
                    </span>
                </div>
            </div>
            <p class="text-white mt-1">Manage your personal information and preferences</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- Profile Picture & Basic Info Section -->
                <div class="xl:col-span-1 space-y-6">
                    <div class="bg-base-100 rounded-xl p-6 text-center border border-base-300 shadow-sm">
                        <div class="avatar mb-4">
                            <div class="w-32 h-32 rounded-full mx-auto ring ring-primary ring-offset-2 ring-offset-base-100 shadow-lg">
                                <img id="modal-profile-picture" src="{{ asset('images/default.jpg') }}" alt="Profile Picture" class="object-cover" />
                            </div>
                        </div>
                        <div class="form-control">
                            <input type="file" id="profile-picture-input" accept="image/*" class="file-input file-input-bordered file-input-sm w-full max-w-xs hidden" />
                            <button onclick="document.getElementById('profile-picture-input').click()" class="btn btn-outline btn-primary btn-sm w-full">
                                <i class="bx bx-upload mr-2"></i>Change Photo
                            </button>
                        </div>
                        <div class="mt-4 space-y-2">
                            <h4 class="font-semibold text-lg text-gray-800" id="modal-fullname">Full Name</h4>
                            <p class="text-sm text-gray-600" id="modal-role">Role</p>
                            <div class="text-xs text-gray-500 mt-2">
                                <i class="bx bx-id-card mr-1"></i>
                                <span id="modal-empid">Employee ID</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Card -->
                    <div class="bg-base-100 rounded-xl p-4 border border-base-300 shadow-sm">
                        <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="bx bx-stats mr-2 text-primary"></i>
                            Account Overview
                        </h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Member Since</span>
                                <span class="font-medium" id="member-since">2024</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="font-medium" id="last-updated">Recently</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Profile Complete</span>
                                <span class="badge badge-success badge-sm">100%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details Section -->
                <div class="xl:col-span-3">
                    <div class="bg-base-100 rounded-xl p-6 border border-base-300 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="font-bold text-xl text-gray-800 flex items-center">
                                <i class="bx bx-user-circle mr-2"></i>
                                Personal Information
                            </h4>
                            <div class="text-sm text-gray-500">
                                <i class="bx bx-info-circle mr-1"></i>
                                All fields marked with * are required
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Personal Details Column -->
                            <div class="space-y-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold flex items-center">
                                            Employee ID
                                            <span class="text-gray-400 ml-1">(Auto-generated)</span>
                                        </span>
                                    </label>
                                    <input type="text" id="profile-empid" class="input input-bordered bg-gray-50 text-gray-600" readonly disabled />
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">First Name *</span>
                                        </label>
                                        <input type="text" id="profile-firstname" class="input input-bordered focus:input-primary" placeholder="Enter first name" />
                                    </div>
                                    
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">Last Name *</span>
                                        </label>
                                        <input type="text" id="profile-lastname" class="input input-bordered focus:input-primary" placeholder="Enter last name" />
                                    </div>
                                </div>
                                
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Middle Name</span>
                                    </label>
                                    <input type="text" id="profile-middlename" class="input input-bordered focus:input-primary" placeholder="Enter middle name" />
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">Sex *</span>
                                        </label>
                                        <select id="profile-sex" class="select select-bordered focus:select-primary">
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">Age *</span>
                                        </label>
                                        <input type="number" id="profile-age" class="input input-bordered focus:input-primary" min="1" max="120" placeholder="Age" />
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & System Info Column -->
                            <div class="space-y-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Email Address *</span>
                                    </label>
                                    <input type="email" id="profile-email" class="input input-bordered focus:input-primary" placeholder="Enter email address" />
                                </div>
                                
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Contact Number *</span>
                                    </label>
                                    <input type="text" id="profile-contact" class="input input-bordered focus:input-primary" placeholder="Enter contact number" />
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Birthdate *</span>
                                    </label>
                                    <input type="date" id="profile-birthdate" class="input input-bordered focus:input-primary" />
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">System Role</span>
                                        </label>
                                        <input type="text" id="profile-role" class="input input-bordered bg-gray-50 text-gray-600" readonly disabled />
                                    </div>
                                    
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">Status</span>
                                        </label>
                                        <input type="text" id="profile-status" class="input input-bordered bg-gray-50 text-gray-600" readonly disabled />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="modal-action mt-8 pt-6 border-t border-base-300">
                            <button class="btn btn-ghost hover:bg-base-200" onclick="closeModal('profile-modal')">Cancel</button>
                            <button class="btn btn-primary shadow-lg hover:shadow-xl transition-all" onclick="updateProfile()">
                                <i class="bx bxs-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="messages-modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Messages</h3>
        <p class="py-4">This feature is still under development.</p>
        <div class="modal-action">
            <button class="btn" onclick="closeModal('messages-modal')">Close</button>
        </div>
    </div>
</div>
<div id="reminder-modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Reminder Calendar</h3>
        <p class="py-4">This feature is still under development.</p>
        <div class="modal-action">
            <button class="btn" onclick="closeModal('reminder-modal')">Close</button>
        </div>
    </div>
</div>
<div id="settings-modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Settings</h3>
        <p class="py-4">This feature is still under development.</p>
        <div class="modal-action">
            <button class="btn" onclick="closeModal('settings-modal')">Close</button>
        </div>
    </div>
</div>

<script>
    // Global modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('modal-open');
            
            // If opening profile modal, load user data immediately
            if (modalId === 'profile-modal') {
                loadUserProfile();
            }
        }
        // Close dropdowns after clicking
        closeAllDropdowns();
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('modal-open');
        }
    }

    function closeAllDropdowns() {
        // Close all DaisyUI dropdowns by removing focus
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('[tabindex="0"]');
            if (button) {
                button.blur();
            }
        });
    }

    // SweetAlert Logout Function
    function handleLogoutWithSweetAlert() {
        closeAllDropdowns();
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out of your account.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'Cancel',
            background: '#fff',
            color: '#333',
            customClass: {
                confirmButton: 'btn btn-error',
                cancelButton: 'btn btn-ghost'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading alert
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Please wait while we secure your session',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Clear all authentication data
                localStorage.removeItem('user');
                localStorage.removeItem('isAuthenticated');
                
                document.cookie = 'isAuthenticated=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                document.cookie = 'user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                
                // Show success alert after 1 second, then redirect
                setTimeout(() => {
                    Swal.close(); // Close the loading alert
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Successfully Logged Out!',
                        text: 'You have been successfully logged out.',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#f0fdf4',
                        color: '#065f46'
                    }).then(() => {
                        // Redirect to logout splash after success alert
                        window.location.href = '/logout-splash';
                    });
                }, 1000);
            }
        });
    }

    // Date format conversion function
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        
        // Handle different date formats
        let date;
        
        // Handle ISO format with timezone
        if (dateString.includes('T')) {
            date = new Date(dateString);
        } 
        // Handle YYYY-MM-DD format
        else if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            return dateString;
        }
        // Handle other formats
        else {
            date = new Date(dateString);
        }
        
        // Check if date is valid
        if (isNaN(date.getTime())) {
            console.warn('Invalid date:', dateString);
            return '';
        }
        
        // Format as YYYY-MM-DD for input[type="date"]
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        
        return `${year}-${month}-${day}`;
    }

    // User profile functions
    function loadUserProfile() {
        console.log('Loading user profile...');
        
        let user = null;
        const localUser = localStorage.getItem('user');
        
        if (localUser) {
            try {
                user = JSON.parse(localUser);
                console.log('User data from localStorage:', user);
            } catch (e) {
                console.error('Error parsing localStorage user data:', e);
            }
        }
        
        if (!user) {
            const userCookie = document.cookie.split('; ').find(row => row.startsWith('user='));
            if (userCookie) {
                try {
                    user = JSON.parse(decodeURIComponent(userCookie.split('=')[1]));
                    console.log('User data from cookie:', user);
                } catch (e) {
                    console.error('Error parsing cookie user data:', e);
                }
            }
        }
        
        if (!user) {
            console.warn('No user data found');
            user = {};
        }
        
        // Format birthdate for input field
        const formattedBirthdate = formatDateForInput(user.birthdate);
        console.log('Original birthdate:', user.birthdate, 'Formatted:', formattedBirthdate);
        
        // Populate the form fields
        document.getElementById('profile-firstname').value = user.firstname || '';
        document.getElementById('profile-lastname').value = user.lastname || '';
        document.getElementById('profile-middlename').value = user.middlename || '';
        document.getElementById('profile-empid').value = user.employee_id || '';
        document.getElementById('profile-email').value = user.Email || user.email || '';
        document.getElementById('profile-contact').value = user.contactnum || '';
        document.getElementById('profile-role').value = user.roles || '';
        document.getElementById('profile-status').value = user.status || '';
        document.getElementById('profile-sex').value = user.sex || '';
        document.getElementById('profile-age').value = user.age || '';
        document.getElementById('profile-birthdate').value = formattedBirthdate;
        
        // Update profile picture and additional info
        updateProfilePicture(user);
        
        // Update full name and role in modal
        document.getElementById('modal-fullname').textContent = `${user.firstname || ''} ${user.lastname || ''}`.trim();
        document.getElementById('modal-role').textContent = user.roles || '';
        document.getElementById('modal-empid').textContent = user.employee_id || '';
        
        // Update status badge
        const statusBadge = document.getElementById('modal-status-badge');
        if (statusBadge) {
            statusBadge.textContent = user.status === 'active' ? 'Active' : 'Inactive';
            statusBadge.className = user.status === 'active' ? 'badge badge-success badge-lg' : 'badge badge-error badge-lg';
        }
    }

    function updateProfilePicture(user) {
        let profilePictureUrl = '{{ asset('images/pfp.jpg') }}';
        
        if (user.profile_picture) {
            // Check if it's already a full URL or just a path
            if (user.profile_picture.startsWith('http')) {
                profilePictureUrl = user.profile_picture;
            } else {
                // Fix: Use the correct path for profile pictures
                // If profile_picture is stored as a relative path in the database
                if (user.profile_picture.startsWith('profile-pictures/')) {
                    profilePictureUrl = `http://localhost:8002/storage/${user.profile_picture}`;
                } else {
                    // If it's just a filename, assume it's in profile-pictures directory
                    profilePictureUrl = `http://localhost:8002/storage/profile-pictures/${user.profile_picture}`;
                }
            }
        }
        
        console.log('Profile picture URL:', profilePictureUrl);
        
        // Set the src and add error handling for broken images
        const modalImg = document.getElementById('modal-profile-picture');
        const headerImg = document.getElementById('header-profile-picture');
        
        modalImg.src = profilePictureUrl;
        headerImg.src = profilePictureUrl;
        
        // Add error handling in case image fails to load
        modalImg.onerror = function() {
            console.warn('Failed to load modal profile picture, using default');
            this.src = '{{ asset('images/pfp.jpg') }}';
        };
        
        headerImg.onerror = function() {
            console.warn('Failed to load header profile picture, using default');
            this.src = '{{ asset('images/default.jpg') }}';
        };
    }

    function updateHeaderWithUserInfo() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const usernameElement = document.getElementById('header-username');
        if (usernameElement && user.firstname) {
            usernameElement.textContent = user.firstname;
        }
        
        // Update header profile picture
        updateProfilePicture(user);
    }

    async function updateProfile() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        
        // Get and validate form data
        const updatedData = {
            id: user.id,
            firstname: document.getElementById('profile-firstname').value.trim(),
            lastname: document.getElementById('profile-lastname').value.trim(),
            middlename: document.getElementById('profile-middlename').value.trim(),
            Email: document.getElementById('profile-email').value.trim(),
            contactnum: document.getElementById('profile-contact').value.trim(),
            sex: document.getElementById('profile-sex').value,
            age: parseInt(document.getElementById('profile-age').value) || 0,
            birthdate: document.getElementById('profile-birthdate').value
        };

        // Validate required fields
        if (!updatedData.firstname || !updatedData.lastname || !updatedData.Email || !updatedData.contactnum) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in all required fields (First Name, Last Name, Email, Contact Number).'
            });
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(updatedData.Email)) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Email',
                text: 'Please enter a valid email address.'
            });
            return;
        }

        // Validate age
        if (updatedData.age < 1 || updatedData.age > 120) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Age',
                text: 'Please enter a valid age between 1 and 120.'
            });
            return;
        }

        // Validate birthdate
        if (!updatedData.birthdate) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Birthdate',
                text: 'Please select your birthdate.'
            });
            return;
        }

        try {
            // Show loading alert (normal alert, not toast)
            Swal.fire({
                title: 'Updating Profile',
                text: 'Please wait while we update your information...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch('/api/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(updatedData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Update localStorage with new user data
                localStorage.setItem('user', JSON.stringify(data.user));
                updateHeaderWithUserInfo();

                // Close the loading alert and show success TOAST
                Swal.close();
                
                // Success toast alert
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Profile updated successfully!'
                });

                // Close modal after toast
                setTimeout(() => {
                    closeModal('profile-modal');
                }, 3000);

            } else {
                // Handle validation errors from backend
                const errorMessage = data.errors ? 
                    Object.values(data.errors).flat().join(', ') : 
                    data.message || 'Failed to update profile';
                
                throw new Error(errorMessage);
            }
        } catch (error) {
            Swal.close(); // Close the loading alert
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: error.message || 'Failed to update profile. Please try again.'
            });
        }
    }

    // Profile picture upload handling
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('profile-picture-input');
        if (profilePictureInput) {
            profilePictureInput.addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const user = JSON.parse(localStorage.getItem('user') || '{}');
                if (!user.id) {
                    Swal.fire('Error', 'User not found', 'error');
                    return;
                }

                // Validate file type and size
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!validTypes.includes(file.type)) {
                    Swal.fire('Error', 'Please select a valid image file (JPEG, PNG, JPG, GIF)', 'error');
                    return;
                }

                if (file.size > maxSize) {
                    Swal.fire('Error', 'Image size should be less than 2MB', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('id', user.id);
                formData.append('profile_picture', file);

                try {
                    // Show loading alert (normal alert, not toast)
                    Swal.fire({
                        title: 'Uploading Picture',
                        text: 'Please wait while we upload your profile picture...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch('http://localhost:8001/api/profile/upload-picture', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Update profile picture in UI
                        document.getElementById('modal-profile-picture').src = data.profile_picture_url;
                        document.getElementById('header-profile-picture').src = data.profile_picture_url;
                        
                        // Update user data in localStorage
                        localStorage.setItem('user', JSON.stringify(data.user));

                        // Close loading and show success TOAST
                        Swal.close();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Profile picture updated!'
                        });
                    } else {
                        throw new Error(data.message || 'Failed to upload picture');
                    }
                } catch (error) {
                    Swal.close(); // Close the loading alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: error.message || 'Failed to upload profile picture. Please try again.'
                    });
                }
            });
        }

        // Initialize everything when DOM is ready
        console.log('Header initialized');
        
        // Update header with current user info
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const usernameElement = document.getElementById('header-username');
        if (usernameElement && user.firstname) {
            usernameElement.textContent = user.firstname;
        }

        // Update header profile picture
        updateProfilePicture(user);

        // Set up profile modal click listener
        const profileModal = document.getElementById('profile-modal');
        if (profileModal) {
            const profileDropdownItems = document.querySelectorAll('#profile-dropdown a[onclick*="profile-modal"]');
            profileDropdownItems.forEach(item => {
                item.addEventListener('click', function() {
                    setTimeout(loadUserProfile, 100);
                });
            });
        }
    });
</script>