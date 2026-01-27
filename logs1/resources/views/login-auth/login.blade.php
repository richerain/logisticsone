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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "brand-primary": "#059669",
                        "brand-primary-hover": "#047857",
                        "brand-background-main": "#F0FDF4",
                        "brand-border": "#D1FAE5",
                        "brand-text-primary": "#1F2937",
                        "brand-text-secondary": "#4B5563",
                    },
                    keyframes: {
                        float: {
                            "0%, 100%": { transform: "translateY(0) translateX(0)" },
                            "25%": { transform: "translateY(-20px) translateX(10px)" },
                            "50%": { transform: "translateY(-40px) translateX(0)" },
                            "75%": { transform: "translateY(-20px) translateX(-10px)" },
                        },
                        "float-reverse": {
                            "0%, 100%": { transform: "translateY(0) translateX(0)" },
                            "33%": { transform: "translateY(25px) translateX(-15px)" },
                            "66%": { transform: "translateY(10px) translateX(15px)" },
                        }
                    },
                    animation: {
                        float: "float 6s ease-in-out infinite",
                        "float-delayed": "float 6s ease-in-out 3s infinite",
                        "float-reverse": "float-reverse 7s ease-in-out infinite",
                        "float-reverse-fast": "float-reverse 5s ease-in-out infinite",
                        "float-fast": "float 5s ease-in-out infinite",
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <main class="flex items-center justify-center min-h-screen bg-brand-primary p-6 relative overflow-hidden">
        <!-- Floating Shapes Background -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="shape absolute w-72 h-72 top-[5%] left-[-5%] bg-white/5 rounded-full animate-float"></div>
            <div class="shape shape-2 absolute w-96 h-96 bottom-[-20%] left-[15%] bg-white/5 rounded-full animate-float-delayed"></div>
            <div class="shape shape-3 absolute w-80 h-80 top-[-15%] right-[-10%] bg-white/5 rounded-full animate-float-reverse"></div>
            <div class="shape shape-4 absolute w-56 h-56 bottom-[5%] right-[10%] bg-white/5 rounded-full animate-float-fast"></div>
            <div class="shape shape-5 absolute w-48 h-48 top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2 bg-white/5 rounded-full animate-float-reverse-fast"></div>
        </div>

        <div class="w-full max-w-6xl rounded-lg overflow-hidden flex flex-col lg:flex-row relative z-10">
            <!-- Left Panel -->
            <section class="hidden lg:flex w-1/2 items-center justify-center p-6 text-white bg-brand-primary/10">
                <div class="flex flex-col items-center w-full py-4">
                    <div class="text-center">
                        <img src="{{ asset('images/micrologo.png') }}" alt="Microfinance Logo" class="w-20 h-20 mx-auto">
                        <h1 class="text-3xl font-bold mt-4">Microfinance Logistics</h1>
                        <p class="text-white/80">Logistics I</p>
                    </div>

                    <!-- Illustration Carousel -->
                    <div class="relative w-full max-w-2xl h-64 my-4">
                        <img src="{{ asset('storage/login-carousel/illustration-1.svg') }}" alt="Illustration 1" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-100">
                        <img src="{{ asset('storage/login-carousel/illustration-2.svg') }}" alt="Illustration 2" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('storage/login-carousel/illustration-3.svg') }}" alt="Illustration 3" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('storage/login-carousel/illustration-4.svg') }}" alt="Illustration 4" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('storage/login-carousel/illustration-5.svg') }}" alt="Illustration 5" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                    </div>

                    <div class="text-center mt-4 max-w-xl">
                        <p class="italic text-white/90 text-base leading-relaxed">
                            “The strength of the team is each individual member. The strength of each member is the team.”
                        </p>
                        <cite class="block text-right mt-2 text-white/60">- Phil Jackson</cite>
                    </div>
                </div>
            </section>
            
            <!-- Right Panel: Login Card -->
            <section class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="bg-white/90 w-full max-w-md backdrop-blur-lg rounded-2xl shadow-2xl p-8">

                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-bold text-brand-text-primary">Welcome Back!</h2>
                        <p class="text-brand-text-secondary mt-1">Please enter your details to sign in.</p>
                    </div>

                    <!-- login failed alert start -->
                    <div id="login-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative hidden mb-4" role="alert">
                        <div class="flex items-center">
                            <i class='bx bxs-error-circle text-red-600 mr-2'></i>
                            <span id="login-error-message" class="text-red-600 text-sm">Login failed. Please check your credentials and try again.</span>
                        </div>
                    </div>
                    <!-- login failed alert end -->

                    <form id="login-form">
                        @csrf
                        <!-- Email -->
                        <div class="relative mb-4">
                            <label class="block text-sm font-medium text-gray-700" for="email">Email Address</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-at text-gray-400 text-lg'></i>
                                </div>
                                <input id="email" name="email" type="email" placeholder="Enter your email"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm
                                            focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                                            transition-all duration-200"
                                    required />
                            </div>
                            <span id="email-error" class="text-red-600 text-sm hidden"></span>
                        </div>

                        <!-- Password -->
                        <div class="relative mb-4">
                            <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                                </div>

                                <input id="password" name="password" type="password" placeholder="Enter your password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm
                                            focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                                            transition-all duration-200"
                                    required />

                                <div id="password-toggle"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer select-none transition-transform duration-150">
                                    <i id="password-icon" class='bx bx-show-alt text-xl text-gray-400 hover:text-brand-primary transition-colors'></i>
                                </div>
                            </div>
                            <span id="password-error" class="text-red-600 text-sm hidden"></span>
                        </div>

                        <!-- Sign In -->
                        <button id="sign-in-btn" type="submit" disabled
                            class="w-full bg-brand-primary text-white font-bold py-3 px-4 rounded-lg
                                    transition-all duration-300 shadow-lg
                                    transform active:translate-y-0 active:scale-[0.99]
                                    opacity-60 cursor-not-allowed">
                            <span id="loginText">Sign In</span>
                            <span id="loginSpinner" class="loading loading-spinner loading-sm hidden"></span>
                        </button>

                        <!-- Terms checkbox below button -->
                        <div class="mt-4 flex items-start gap-3">
                            <input id="terms-check" name="agree" type="checkbox"
                            class="mt-1 h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary transition">
                            <label for="terms-check" class="text-sm text-gray-700 leading-relaxed select-none">
                            I agree to the
                            <button id="terms-link" type="button"
                                class="text-brand-primary hover:text-brand-primary-hover hover:underline transition-colors font-semibold">
                                Terms and Conditions
                            </button>
                            </label>
                        </div>
                        <span id="agree-error" class="text-red-600 text-sm hidden"></span>
                    </form>

                    <div class="text-center mt-8 text-sm">
                        <p class="text-gray-500">&copy; 2025 Microfinance Logistics. All Rights Reserved.</p>
                    </div>
                </div>
            </section>
            <!-- login form panel end -->
        </div>
    </main>

    <!-- Terms & Conditions Modal -->
    <div id="terms-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-brand-background-main flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-brand-border rounded-full flex items-center justify-center">
                        <i class='bx bxs-file-doc text-brand-primary text-xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Terms & Conditions</h3>
                        <p class="text-sm text-gray-600">Please read and accept the terms to continue</p>
                    </div>
                </div>
                <button id="close-terms-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 flex-1 overflow-y-auto">
                <div class="space-y-6">
                    <!-- Introduction -->
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Microfinancial Logistics I System</h2>
                        <p class="text-gray-600">Terms of Service and User Agreement</p>
                    </div>

                    <!-- Last Updated -->
                    <div class="bg-brand-background-main border border-brand-border rounded-lg p-4">
                        <div class="flex items-center">
                            <i class='bx bxs-calendar text-brand-primary mr-2'></i>
                            <span class="text-sm text-brand-text-secondary">Last Updated: November 2025</span>
                        </div>
                    </div>

                    <!-- Terms Content -->
                    <div class="space-y-6">
                        <!-- Section 1 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">1. Acceptance of Terms</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                By accessing and using the Microfinancial Logistics I System, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you must not use this system.
                            </p>
                        </div>

                        <!-- Section 2 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">2. User Responsibilities</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p class="flex items-start">
                                    <i class='bx bxs-check-circle text-brand-primary mr-2 mt-0.5 flex-shrink-0'></i>
                                    <span>You are responsible for maintaining the confidentiality of your login credentials and for all activities that occur under your account.</span>
                                </p>
                                <p class="flex items-start">
                                    <i class='bx bxs-check-circle text-brand-primary mr-2 mt-0.5 flex-shrink-0'></i>
                                    <span>You must provide accurate and complete information during registration and keep it updated.</span>
                                </p>
                                <p class="flex items-start">
                                    <i class='bx bxs-check-circle text-brand-primary mr-2 mt-0.5 flex-shrink-0'></i>
                                    <span>You agree to use the system only for legitimate business purposes related to logistics management.</span>
                                </p>
                            </div>
                        </div>

                        <!-- Section 3 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">3. System Usage</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>The Microfinancial Logistics I System provides the following modules:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Procurement & Sourcing Management (PSM)</li>
                                    <li>Smart Warehousing System (SWS)</li>
                                    <li>Project Logistics Tracker (PLT)</li>
                                    <li>Asset Lifecycle & Maintenance (ALMS)</li>
                                    <li>Document Tracking & Logistics Record (DTLR)</li>
                                </ul>
                                <p>You agree to use these modules in accordance with their intended purposes and company policies.</p>
                            </div>
                        </div>

                        <!-- Section 4 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">4. Data Privacy and Security</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                We are committed to protecting your privacy and the security of your data. All personal and business information is stored securely and accessed only by authorized personnel. By using this system, you consent to the collection and processing of your data as described in our Privacy Policy.
                            </p>
                        </div>

                        <!-- Section 5 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">5. Prohibited Activities</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>You agree not to:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Attempt to gain unauthorized access to any part of the system</li>
                                    <li>Interfere with or disrupt the system's functionality</li>
                                    <li>Share your login credentials with others</li>
                                    <li>Use the system for any illegal or unauthorized purpose</li>
                                    <li>Upload or transmit malicious code or viruses</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section 6 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">6. System Availability</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                We strive to maintain 24/7 system availability but do not guarantee uninterrupted access. Scheduled maintenance may occur periodically, and we reserve the right to modify or discontinue any system feature with reasonable notice.
                            </p>
                        </div>

                        <!-- Section 7 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">7. Intellectual Property</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                All content, features, and functionality of the Microfinancial Logistics I System are the exclusive property of the company and are protected by intellectual property laws. You may not reproduce, distribute, or create derivative works without explicit permission.
                            </p>
                        </div>

                        <!-- Section 8 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">8. Termination</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                We reserve the right to suspend or terminate your access to the system at our discretion, without prior notice, for violations of these terms or for any other reason we deem appropriate.
                            </p>
                        </div>

                        <!-- Section 9 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">9. Limitation of Liability</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                To the fullest extent permitted by law, the company shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use or inability to use the system.
                            </p>
                        </div>

                        <!-- Section 10 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">10. Changes to Terms</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                We may update these Terms and Conditions from time to time. Continued use of the system after such changes constitutes your acceptance of the new terms. We will notify users of significant changes through system notifications or email.
                            </p>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gray-50 rounded-lg p-4 mt-6">
                            <h5 class="font-semibold text-gray-800 mb-2">Contact Information</h5>
                            <p class="text-sm text-gray-600">
                                For questions about these Terms and Conditions, please contact the system administrator at 
                                <a href="mailto:logistic1.microfinancial@gmail.com" class="text-brand-primary hover:underline">logistic1.microfinancial@gmail.com</a> 
                                or your department supervisor.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class='bx bxs-info-circle text-brand-primary'></i>
                    <span>By clicking "Yes, I Agree" you accept all terms and conditions</span>
                </div>
                <div class="flex space-x-3">
                    <button id="decline-terms" class="btn btn-ghost text-gray-600 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button id="accept-terms" class="btn bg-brand-primary hover:bg-brand-primary-hover text-white border-none">
                        <i class='bx bxs-check-circle mr-2'></i>Yes, I Agree
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug helper
    console.log('Login form loaded successfully');

    // Carousel Functionality
    const svgs = document.querySelectorAll('.login-svg');
    let currentIndex = 0;
    
    if(svgs.length > 0) {
        setInterval(() => {
            // Fade out current
            svgs[currentIndex].classList.remove('opacity-100');
            svgs[currentIndex].classList.add('opacity-0');
            
            // Move to next
            currentIndex = (currentIndex + 1) % svgs.length;
            
            // Fade in next
            svgs[currentIndex].classList.remove('opacity-0');
            svgs[currentIndex].classList.add('opacity-100');
        }, 4000); // Change every 4 seconds
    }

    // Password Toggle Functionality
    const passwordToggle = document.getElementById('password-toggle');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');

    if (passwordToggle && passwordInput && passwordIcon) {
        passwordToggle.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bx-show-alt');
                passwordIcon.classList.add('bx-hide');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bx-hide');
                passwordIcon.classList.add('bx-show-alt');
            }
        });
    }

    // Terms Checkbox & Sign In Button State
    const termsCheck = document.getElementById('terms-check');
    const signInBtn = document.getElementById('sign-in-btn');

    if (termsCheck && signInBtn) {
        termsCheck.addEventListener('change', function() {
            if (this.checked) {
                signInBtn.disabled = false;
                signInBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                signInBtn.classList.add('hover:bg-brand-primary-hover', 'active:translate-y-0', 'active:scale-[0.99]');
            } else {
                signInBtn.disabled = true;
                signInBtn.classList.add('opacity-60', 'cursor-not-allowed');
                signInBtn.classList.remove('hover:bg-brand-primary-hover', 'active:translate-y-0', 'active:scale-[0.99]');
            }
        });
    }

    // Login Form Submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleLogin();
        });
    }

    // Terms & Conditions Modal Functionality
    const termsLink = document.getElementById('terms-link');
    const termsModal = document.getElementById('terms-modal');
    const closeTermsModal = document.getElementById('close-terms-modal');
    const declineTerms = document.getElementById('decline-terms');
    const acceptTerms = document.getElementById('accept-terms');
    // Re-select checkbox here or use the one defined above
    const agreeCheckbox = document.getElementById('terms-check');

    // Open terms modal
    if (termsLink && termsModal) {
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close terms modal
    function closeTermsModalFunc() {
        if (termsModal) {
            termsModal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    if (closeTermsModal) closeTermsModal.addEventListener('click', closeTermsModalFunc);
    if (declineTerms) declineTerms.addEventListener('click', closeTermsModalFunc);

    // Accept terms and conditions
    if (acceptTerms) {
        acceptTerms.addEventListener('click', function() {
            // Check the checkbox
            if (agreeCheckbox) {
                agreeCheckbox.checked = true;
                // Trigger change event to update button state
                agreeCheckbox.dispatchEvent(new Event('change'));
            }
            
            // Close the modal
            closeTermsModalFunc();
            
            // Optional: Show a brief confirmation
            const originalText = acceptTerms.innerHTML;
            acceptTerms.innerHTML = '<i class="bx bxs-check-circle mr-2"></i>Accepted!';
            acceptTerms.disabled = true;
            
            setTimeout(() => {
                acceptTerms.innerHTML = originalText;
                acceptTerms.disabled = false;
            }, 1500);
        });
    }

    // Close modal when clicking outside
    if (termsModal) {
        termsModal.addEventListener('click', function(e) {
            if (e.target === termsModal) {
                closeTermsModalFunc();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && termsModal && !termsModal.classList.contains('hidden')) {
            closeTermsModalFunc();
        }
    });
});

function handleLogin() {
    // Reset errors
    document.querySelectorAll('[id$="-error"]').forEach(el => {
        el.classList.add('hidden');
    });

    // Hide login error alert
    const loginError = document.getElementById('login-error');
    if (loginError) loginError.classList.add('hidden');

    const form = document.getElementById('login-form');
    const formData = new FormData(form);
    
    // Basic validation
    const email = formData.get('email');
    const password = formData.get('password');
    const agree = document.getElementById('terms-check').checked;

    let hasError = false;

    if (!email) {
        showError('email-error', 'Email is required');
        hasError = true;
    }

    if (!password) {
        showError('password-error', 'Password is required');
        hasError = true;
    }

    if (!agree) {
        showError('agree-error', 'You must agree to the terms and conditions');
        hasError = true;
    }

    if (hasError) {
        return;
    }

    // Show loading state
    const loginBtn = document.getElementById('sign-in-btn');
    const loginText = document.getElementById('loginText');
    const loginSpinner = document.getElementById('loginSpinner');

    if (loginBtn) loginBtn.disabled = true;
    if (loginText) loginText.textContent = 'Logging in...';
    if (loginSpinner) loginSpinner.classList.remove('hidden');

    // Get CSRF token from meta tag
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    console.log('Making login request with CSRF token:', csrfToken ? 'Present' : 'Missing');

    // Make API request with CSRF token
    fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'include',
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => {
        console.log('Login response status:', response.status);
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Login failed with status: ${response.status}`);
            }).catch(() => {
                throw new Error(`Login failed with status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Login response data:', data);
        if (data.success) {
            if (data.requires_otp) {
                if (data.otp_debug) {
                    console.log('OTP Code:', data.otp_debug);
                }
                window.location.href = `/otp-verification?email=${encodeURIComponent(data.email)}`;
            } else {
                window.location.href = '/splash-login';
            }
        } else {
            throw new Error(data.message || 'Login failed. Please check your credentials and try again.');
        }
    })
    .catch(error => {
        console.error('Login error:', error);
        
        const loginError = document.getElementById('login-error');
        const loginErrorMessage = document.getElementById('login-error-message');
        
        if (loginError && loginErrorMessage) {
            loginErrorMessage.textContent = error.message;
            loginError.classList.remove('hidden');
            loginError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            setTimeout(() => {
                loginError.classList.add('hidden');
            }, 8000);
        }
    })
    .finally(() => {
        if (loginBtn) loginBtn.disabled = false;
        if (loginText) loginText.textContent = 'Sign In';
        if (loginSpinner) loginSpinner.classList.add('hidden');
    });
}

function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
}
</script>
</body>
</html>
