<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body>
    <main class="flex items-center justify-center min-h-screen bg-green-700 p-6">
        <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden flex flex-col md:flex-row">
            <!-- Logo / branding panel start -->
            <div class="md:w-1/2 bg-gray-900 p-8 flex items-center justify-center">
                <div class="text-center">
                    <img src="{{ asset('images/micrologo.png') }}"
                         alt="Microfinancial Logistics Logo"
                         class="w-20 h-20 sm:w-24 sm:h-24 md:w-40 md:h-40 lg:w-56 lg:h-56 mx-auto mb-4 object-contain" />
                    <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-white">Microfinancial Logistics I</h3>
                    <p class="text-xs sm:text-sm text-green-100 mt-2">Secure access to your Logistics Dashboard.</p>
                </div>
            </div>
            <!-- Logo / branding panel end -->
            <!-- login form panel start -->
            <div class="md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-gray-700 text-center">Microfinancial Logistics Login</h2>
                <p class="text-center text-gray-600 mb-6">Please enter your credentials to access the system.</p>

<!-- login form start -->
<form id="loginForm" class="space-y-4" novalidate>
    @csrf <!-- Add CSRF token -->
    
    <!-- email field start -->
    <label class="block">
        <span class="text-gray-700">Email</span>
        <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bx bxs-envelope text-gray-400 text-lg" aria-hidden="true"></i>
            </span>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="mail@site.com"
                class="input input-bordered w-full pl-10 mt-0"
                aria-label="Email address"
                required
            />
        </div>
        <span id="email-error" class="text-red-600 text-sm hidden"></span>
    </label>
    <!-- email field end -->
    
    <!-- password field start -->
    <label class="block relative">
        <span class="text-gray-700">Password</span>
        <div class="relative mt-1">
            <!-- left lock icon -->
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bx bxs-lock text-gray-400 text-lg" aria-hidden="true"></i>
            </span>

            <input
                id="password"
                name="password"
                type="password"
                placeholder="Password"
                class="input input-bordered w-full pl-10 pr-12 mt-0"
                aria-label="Password"
                required
            />

            <!-- toggle show/hide button -->
            <button
                type="button"
                onclick="togglePassword(this)"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500"
                aria-pressed="false"
                aria-label="Show password"
            >
                <i class="bx bx-show-alt text-lg"></i>
            </button>
        </div>
        <span id="password-error" class="text-red-600 text-sm hidden"></span>
    </label>
    <!-- password field end -->
    
    <!-- Terms and Conditions checkbox section start-->
    <div class="flex items-center justify-between">
        <label for="agree" class="flex items-center space-x-2 cursor-pointer">
            <input id="agree" name="agree" type="checkbox"
                   class="checkbox h-4 w-4 md:h-5 md:w-5 shrink-0 align-middle"
                   aria-checked="false" required />
            <span class="text-sm text-gray-600 leading-tight">
                I have read and agree to the
                <a href="#" class="text-blue-600 hover:underline">Terms &amp; Conditions</a>
            </span>
        </label>
    </div>
    <span id="agree-error" class="text-red-600 text-sm hidden"></span>
    <!-- Terms and Conditions checkbox section end-->

    <button type="submit" id="loginBtn" class="btn btn-primary w-full">
        <span id="loginText">Login</span>
        <span id="loginSpinner" class="loading loading-spinner loading-sm hidden"></span>
    </button>
</form>
<!-- login form end -->
            </div>
            <!-- login form panel end -->
        </div>
    </main>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleLogin();
    });

    function handleLogin() {
        // Reset errors
        document.querySelectorAll('[id$="-error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });

        const formData = new FormData(document.getElementById('loginForm'));
        
        // Basic validation
        const email = formData.get('email');
        const password = formData.get('password');
        const agree = formData.get('agree');

        if (!email) {
            showError('email-error', 'Email is required');
            return;
        }

        if (!password) {
            showError('password-error', 'Password is required');
            return;
        }

        if (!agree) {
            showError('agree-error', 'You must agree to the terms and conditions');
            return;
        }

        // Show loading state
        const loginBtn = document.getElementById('loginBtn');
        const loginText = document.getElementById('loginText');
        const loginSpinner = document.getElementById('loginSpinner');

        loginBtn.disabled = true;
        loginText.textContent = 'Logging in...';
        loginSpinner.classList.remove('hidden');

        // Make API request with CSRF token
        fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Login response:', data);
            if (data.success) {
                if (data.requires_otp) {
                    // Redirect to OTP verification
                    window.location.href = `/otp-verification?email=${encodeURIComponent(data.email)}`;
                } else {
                    // Direct login success
                    window.location.href = '/home';
                }
            } else {
                throw new Error(data.message || 'Login failed');
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: error.message || 'An error occurred during login. Please try again.'
            });
        })
        .finally(() => {
            // Reset loading state
            loginBtn.disabled = false;
            loginText.textContent = 'Login';
            loginSpinner.classList.add('hidden');
        });
    }

    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    // Toggle password visibility
    function togglePassword(btn){
        var input = document.getElementById('password');
        var icon = btn.querySelector('i');
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-show-alt');
            icon.classList.add('bx-hide');
            btn.setAttribute('aria-pressed', 'true');
            btn.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show-alt');
            btn.setAttribute('aria-pressed', 'false');
            btn.setAttribute('aria-label', 'Show password');
        }
    }
</script>
</body>
</html>