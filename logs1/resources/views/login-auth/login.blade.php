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
    <!-- Add CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <p class="text-xs sm:text-sm text-green-100 mt-2">Secure access to Logistics I System.</p>
                </div>
            </div>
            <!-- Logo / branding panel end -->
            <!-- login form panel start -->
            <div class="md:w-1/2 p-8">
                <h2 class="text-3xl font-bold text-gray-700 text-center">Login</h2>
                <p class="text-center text-gray-600 mb-6">Please enter your credentials to access the system.</p>

                <!-- login form start -->
                <form id="loginForm" class="space-y-4" novalidate>
                    @csrf <!-- Add CSRF token -->
                    
                    <!-- login failed alert start -->
                    <div id="login-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative hidden" role="alert">
                        <div class="flex items-center">
                            <i class='bx bxs-error-circle text-red-600 mr-2'></i>
                            <span id="login-error-message" class="text-red-600 text-sm">Login failed. Please check your credentials and try again.</span>
                        </div>
                    </div>
                    <!-- login failed alert end -->
                    
                    <!-- email field start -->
                    <label class="block">
                        <span class="text-gray-700">Email</span>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bx bxs-envelope text-gray-400 text-lg" aria-hidden="true"></i>
                            </span>
                            <input type="email" name="email" id="email" placeholder="mail@site.com" class="input input-bordered w-full pl-10 mt-0" aria-label="Email address" required/>
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
                            <input id="password" name="password" type="password" placeholder="Password" class="input input-bordered w-full pl-10 pr-12 mt-0" aria-label="Password" required/>
                            <!-- toggle show/hide button -->
                            <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500" aria-pressed="false" aria-label="Show password">
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
                                <a href="#" id="terms-link" class="text-blue-600 hover:underline">Terms &amp; Conditions</a>
                            </span>
                        </label>
                    </div>
                    <span id="agree-error" class="text-red-600 text-sm hidden"></span>
                    <!-- Terms and Conditions checkbox section end -->

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

    <!-- Terms & Conditions Modal -->
    <div id="terms-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-green-50 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class='bx bxs-file-doc text-green-600 text-xl'></i>
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
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class='bx bxs-calendar text-blue-600 mr-2'></i>
                            <span class="text-sm text-blue-800">Last Updated: November 2025</span>
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
                                    <i class='bx bxs-check-circle text-green-600 mr-2 mt-0.5 flex-shrink-0'></i>
                                    <span>You are responsible for maintaining the confidentiality of your login credentials and for all activities that occur under your account.</span>
                                </p>
                                <p class="flex items-start">
                                    <i class='bx bxs-check-circle text-green-600 mr-2 mt-0.5 flex-shrink-0'></i>
                                    <span>You must provide accurate and complete information during registration and keep it updated.</span>
                                </p>
                                <p class="flex items-start">
                                    <i class='bx bxs-check-circle text-green-600 mr-2 mt-0.5 flex-shrink-0'></i>
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
                                <a href="mailto:logistic1.microfinancial@gmail.com" class="text-blue-600 hover:underline">logistic1.microfinancial@gmail.com</a> 
                                or your department supervisor.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class='bx bxs-info-circle text-blue-600'></i>
                    <span>By clicking "Yes, I Agree" you accept all terms and conditions</span>
                </div>
                <div class="flex space-x-3">
                    <button id="decline-terms" class="btn btn-ghost text-gray-600 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button id="accept-terms" class="btn btn-primary bg-green-600 hover:bg-green-700 text-white">
                        <i class='bx bxs-check-circle mr-2'></i>Yes, I Agree
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleLogin();
    });

    // Terms & Conditions Modal Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const termsLink = document.getElementById('terms-link');
        const termsModal = document.getElementById('terms-modal');
        const closeTermsModal = document.getElementById('close-terms-modal');
        const declineTerms = document.getElementById('decline-terms');
        const acceptTerms = document.getElementById('accept-terms');
        const agreeCheckbox = document.getElementById('agree');

        // Open terms modal
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        // Close terms modal
        function closeTermsModalFunc() {
            termsModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        closeTermsModal.addEventListener('click', closeTermsModalFunc);
        declineTerms.addEventListener('click', closeTermsModalFunc);

        // Accept terms and conditions
        acceptTerms.addEventListener('click', function() {
            // Check the checkbox
            agreeCheckbox.checked = true;
            agreeCheckbox.setAttribute('aria-checked', 'true');
            
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

        // Close modal when clicking outside
        termsModal.addEventListener('click', function(e) {
            if (e.target === termsModal) {
                closeTermsModalFunc();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !termsModal.classList.contains('hidden')) {
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
        loginError.classList.add('hidden');

        const formData = new FormData(document.getElementById('loginForm'));
        
        // Basic validation
        const email = formData.get('email');
        const password = formData.get('password');
        const agree = document.getElementById('agree').checked;

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
        const loginBtn = document.getElementById('loginBtn');
        const loginText = document.getElementById('loginText');
        const loginSpinner = document.getElementById('loginSpinner');

        loginBtn.disabled = true;
        loginText.textContent = 'Logging in...';
        loginSpinner.classList.remove('hidden');

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Make API request with CSRF token
        fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        })
        .then(response => {
            // First check if response is ok, then parse JSON
            if (!response.ok) {
                // If response is not ok, try to parse error message
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Login failed. Please check your credentials and try again.');
                }).catch(() => {
                    // If JSON parsing fails, use the exact wording provided
                    throw new Error('Login failed. Please check your credentials and try again.');
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
                // Handle API success: false case - use the exact wording provided
                throw new Error('Login failed. Please check your credentials and try again.');
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            
            // Show login error alert with the exact wording provided
            const loginError = document.getElementById('login-error');
            const loginErrorMessage = document.getElementById('login-error-message');
            
            loginErrorMessage.textContent = 'Login failed. Please check your credentials and try again.';
            loginError.classList.remove('hidden');
            
            // Scroll to error message
            loginError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto-hide error after 8 seconds
            setTimeout(() => {
                loginError.classList.add('hidden');
            }, 8000);
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

    // Debug helper - you can remove this after testing
    console.log('Login form loaded successfully');
</script>
</body>
</html>