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
                        },
                        slideInDown: {
                            "0%": { transform: "translateY(-50px)", opacity: "0" },
                            "100%": { transform: "translateY(0)", opacity: "1" },
                        },
                        slideInUp: {
                            "0%": { transform: "translateY(50px)", opacity: "0" },
                            "100%": { transform: "translateY(0)", opacity: "1" },
                        },
                        slideInLeft: {
                            "0%": { transform: "translateX(-30px)", opacity: "0" },
                            "100%": { transform: "translateX(0)", opacity: "1" },
                        },
                        popUp: {
                            "0%": { transform: "scale(0.9)", opacity: "0" },
                            "100%": { transform: "scale(1)", opacity: "1" },
                        }
                    },
                    animation: {
                        float: "float 6s ease-in-out infinite",
                        "float-delayed": "float 6s ease-in-out 3s infinite",
                        "float-reverse": "float-reverse 7s ease-in-out infinite",
                        "float-reverse-fast": "float-reverse 5s ease-in-out infinite",
                        "float-fast": "float 5s ease-in-out infinite",
                        "entrance-down": "slideInDown 0.8s ease-out forwards",
                        "entrance-up": "slideInUp 0.8s ease-out forwards",
                        "entrance-left": "slideInLeft 0.8s ease-out forwards",
                        "entrance-pop": "popUp 0.6s ease-out forwards",
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
                    <div class="text-center opacity-0 animate-entrance-down"><!-- left-panel-company-info start -->
                        <img src="{{ asset('images/micrologo.png') }}" alt="Microfinance Logo" class="w-32 h-32 mx-auto">
                        <h1 class="text-3xl font-bold mt-4">Microfinance Logistics</h1>
                        <p class="text-white/80">Logistics I</p>
                    </div><!-- left-panel-company-info end -->

                    <!-- Illustration Carousel -->
                    <div class="relative w-full max-w-2xl h-64 my-4 opacity-0 animate-entrance-pop delay-[500ms]">
                        <img src="{{ asset('images/login-img/illustration-1.svg') }}" alt="Illustration 1" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-100">
                        <img src="{{ asset('images/login-img/illustration-2.svg') }}" alt="Illustration 2" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/login-img/illustration-3.svg') }}" alt="Illustration 3" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/login-img/illustration-4.svg') }}" alt="Illustration 4" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/login-img/illustration-5.svg') }}" alt="Illustration 5" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                    </div>

                    <div class="text-center mt-4 max-w-xl opacity-0 animate-entrance-pop delay-[500ms]">
                        <p class="italic text-white/90 text-base leading-relaxed">
                            “The strength of the team is each individual member. The strength of each member is the team.”
                        </p>
                        <cite class="block text-right mt-2 text-white/60">- Phil Jackson</cite>
                    </div>
                </div>
            </section>
            
            <!-- Right Panel: Login Card -->
            <section class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="bg-white/90 w-full max-w-md backdrop-blur-lg rounded-2xl shadow-2xl p-8 opacity-0 animate-entrance-up"><!-- right-panel-card start -->

                    <div class="text-center mb-6 opacity-0 animate-entrance-left delay-[500ms]">
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
                        <div class="relative mb-4 opacity-0 animate-entrance-left delay-[500ms]">
                            <label class="block text-sm font-medium text-gray-700" for="email">Email Address</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-at text-gray-400 text-lg'></i>
                                </div>
                                <input id="email" name="email" type="email" placeholder="Enter your email"
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm
                                            focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                                            transition-all duration-200" />
                            </div>
                            <span id="email-error" class="text-red-600 text-sm hidden"></span>
                        </div>

                        <!-- Password -->
                        <div class="relative mb-4 opacity-0 animate-entrance-left delay-[500ms]">
                            <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                                </div>

                                <input id="password" name="password" type="password" placeholder="Enter your password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm
                                            focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                                            transition-all duration-200" />

                                <div id="password-toggle"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer select-none transition-transform duration-150">
                                    <i id="password-icon" class='bx bx-show-alt text-xl text-gray-400 hover:text-brand-primary transition-colors'></i>
                                </div>
                            </div>
                            <span id="password-error" class="text-red-600 text-sm hidden"></span>
                        </div>

                        <!-- Terms checkbox below button -->
                        <div class="mt-4 mb-4 opacity-0 animate-entrance-pop delay-[1000ms]">
                            <div class="flex items-start gap-3">
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
                            <span id="agree-error" class="text-red-600 text-sm hidden block mt-1"></span>
                        </div>

                        <!-- Sign In -->
                        <button id="sign-in-btn" type="submit"
                            class="w-full bg-brand-primary text-white font-bold py-3 px-4 rounded-lg
                                    transition-all duration-300 shadow-lg
                                    transform active:translate-y-0 active:scale-[0.99] hover:bg-brand-primary-hover opacity-0 animate-entrance-pop delay-[1000ms]">
                            <span id="loginText">Sign In</span>
                            <span id="loginSpinner" class="loading loading-spinner loading-sm hidden"></span>
                        </button>

                        <!-- Vendor Login Button -->
                        <div class="mt-4 text-center opacity-0 animate-entrance-pop delay-[1000ms]">
                            <a href="/login/vendor-portal" 
                                class="inline-block text-brand-primary hover:text-brand-primary-hover hover:underline transition-colors font-semibold text-sm">
                                Vendor Here!
                            </a>
                        </div>
                    </form>

                    <div class="text-center mt-8 text-sm opacity-0 animate-entrance-pop delay-[1000ms]">
                        <p class="text-gray-500">&copy; 2026 Microfinance Logistics. All Rights Reserved.</p>
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
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">1. System Usage & Authorization</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                Access to the Microfinancial Logistics I System is restricted to authorized personnel only. Unauthorized access, use, or modification of this system is strictly prohibited and may result in disciplinary action or legal prosecution. By logging in, you agree to comply with the organization’s Information Security Policy (ISO/IEC 27001 Annex A.9).
                            </p>
                        </div>

                        <!-- Section 2 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">2. User Accountability</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>You are responsible for all activities conducted under your user account (ISO/IEC 27001 Annex A.9.2). You must:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Keep your login credentials confidential and not share them with anyone.</li>
                                    <li>Lock your screen or log out when leaving your workstation unattended.</li>
                                    <li>Report any suspicious activity or unauthorized access immediately.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section 3 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">3. Data Protection & Confidentiality</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                This system processes sensitive business and personal data. You are required to handle all information in accordance with Data Protection Laws and the organization’s Data Classification Guidelines (ISO/IEC 27001 Annex A.8). Disclosure of confidential information to unauthorized parties is a violation of company policy.
                            </p>
                        </div>

                        <!-- Section 4 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">4. Operational Security & Audit Logging</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                All user activities within the system are logged and monitored for security and auditing purposes (ISO/IEC 27001 Annex A.12.4). The organization reserves the right to review these logs to ensure compliance with security policies and to investigate incidents.
                            </p>
                        </div>

                        <!-- Section 5 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">5. Prohibited Actions</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>Users must not:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Attempt to bypass security controls or access data beyond their authorized level.</li>
                                    <li>Install unauthorized software or use the system for personal gain.</li>
                                    <li>Introduce malicious software (viruses, ransomware, etc.) into the network.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section 6 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">6. Incident Reporting</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                Any security incident, including lost passwords, data breaches, or system anomalies, must be reported immediately to the IT Security Team or System Administrator (ISO/IEC 27001 Annex A.16). Failure to report incidents may result in disciplinary action.
                            </p>
                        </div>

                        <!-- Section 7 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">7. Access Revocation</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                The organization reserves the right to revoke or suspend user access at any time without prior notice if a violation of these terms is detected or upon termination of employment.
                            </p>
                        </div>

                        <!-- Section 8 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">8. Authorized Modules & Access Rights</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>Access is granted only to modules required for your role:</p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Procurement & Sourcing Management (PSM)</li>
                                    <li>Smart Warehousing System (SWS)</li>
                                    <li>Project Logistics Tracker (PLT)</li>
                                    <li>Asset Lifecycle & Maintenance (ALMS)</li>
                                    <li>Document Tracking & Logistics Record (DTLR)</li>
                                </ul>
                                <p>Attempting to access restricted modules constitutes a security violation.</p>
                            </div>
                        </div>

                        <!-- Section 9 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">9. Compliance & Disciplinary Action</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                Non-compliance with these Terms & Conditions constitutes a breach of company policy and may lead to disciplinary actions, up to and including termination of employment and legal action.
                            </p>
                        </div>

                        <!-- Section 10 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">10. Amendments</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                These terms may be updated periodically to reflect changes in security standards (e.g., ISO 27001 revisions) or company policies. Users will be notified of significant changes upon login.
                            </p>
                        </div>

                        <!-- Section 11 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">11. Governing Law</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">
                                These terms are governed by the laws applicable to the organization’s jurisdiction and internal regulations.
                            </p>
                        </div>

                        <!-- Section 12 -->
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">12. Contact Information</h4>
                            <div class="bg-gray-50 rounded-lg p-4 mt-2">
                                <p class="text-sm text-gray-600 mb-2">
                                    For security concerns or clarifications regarding these terms, please contact:
                                </p>
                                <div class="flex items-center text-sm text-brand-primary font-medium">
                                    <i class='bx bxs-envelope mr-2'></i>
                                    <a href="mailto:logistic1.microfinancial@gmail.com" class="hover:underline">logistic1.microfinancial@gmail.com</a>
                                </div>
                            </div>
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
    console.log('Login Script Loaded - v1.1 (Fixed Route)');
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
    fetch('/api/v1/auth/login', {
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
                console.error('Login error details:', errorData);
                throw new Error(errorData.message || "Incorrect credential please try again!");
            }).catch(e => {
                console.error('Login error parsing:', e);
                throw new Error("Incorrect credential please try again!");
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
