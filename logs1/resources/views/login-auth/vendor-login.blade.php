<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}" />
    <title>Vendor Portal Login</title>
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
                    <div class="text-center opacity-0 animate-entrance-down">
                        <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Vendors Logo" class="w-32 h-32 mx-auto">
                        <h1 class="text-3xl font-bold mt-4">Microfinance Vendors</h1>
                        <p class="text-white/80">Vendor Portal</p>
                    </div>

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
            <section class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8">
                <div class="bg-white/90 w-full max-w-md backdrop-blur-lg rounded-2xl shadow-2xl p-8 opacity-0 animate-entrance-up">
                    <div class="text-center mb-6 opacity-0 animate-entrance-left delay-[500ms]">
                        <h2 class="text-3xl font-bold text-brand-text-primary">Vendor Portal</h2>
                        <p class="text-brand-text-secondary mt-1">Please enter your details to sign in.</p>
                    </div>

                    <form id="loginForm" class="space-y-4" novalidate>
                        @csrf
                        <!-- login failed alert start -->
                        <div id="login-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative hidden mb-4" role="alert">
                            <div class="flex items-center">
                                <i class='bx bxs-error-circle text-red-600 mr-2'></i>
                                <span id="login-error-message" class="text-red-600 text-sm">Login failed. Please check your credentials and try again.</span>
                            </div>
                        </div>
                        <!-- login failed alert end -->

                        <!-- Email -->
                        <div class="relative mb-4 opacity-0 animate-entrance-left delay-[500ms]">
                            <label class="block text-sm font-medium text-gray-700" for="email">Email Address</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-at text-gray-400 text-lg'></i>
                                </div>
                                <input type="email" name="email" id="email" placeholder="Enter your email" 
                                    class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm
                                            focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                                            transition-all duration-200"
                                    aria-label="Email address" required/>
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
                                            transition-all duration-200"
                                    aria-label="Password" required/>
                                <div id="password-toggle" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer select-none transition-transform duration-150" onclick="togglePassword(this)">
                                    <i id="password-icon" class='bx bx-show-alt text-xl text-gray-400 hover:text-brand-primary transition-colors'></i>
                                </div>
                            </div>
                            <span id="password-error" class="text-red-600 text-sm hidden"></span>
                        </div>

                        <!-- Terms checkbox -->
                        <div class="mt-4 mb-4 flex items-start gap-3 opacity-0 animate-entrance-pop delay-[1000ms]">
                            <input id="agree" name="agree" type="checkbox"
                            class="mt-1 h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary transition" required />
                            <label for="agree" class="text-sm text-gray-700 leading-relaxed select-none">
                            I agree to the
                            <button id="terms-link" type="button"
                                class="text-brand-primary hover:text-brand-primary-hover hover:underline transition-colors font-semibold">
                                Terms &amp; Conditions
                            </button>
                            </label>
                        </div>
                        <span id="agree-error" class="text-red-600 text-sm hidden"></span>

                        <!-- Sign In Button -->
                        <button type="submit" id="loginBtn" 
                            class="w-full bg-brand-primary text-white font-bold py-3 px-4 rounded-lg
                                    transition-all duration-300 shadow-lg
                                    transform active:translate-y-0 active:scale-[0.99] opacity-0 animate-entrance-pop delay-[1000ms]">
                            <span id="loginText">Sign In</span>
                            <span id="loginSpinner" class="loading loading-spinner loading-sm hidden"></span>
                        </button>
                        
                        <div class="mt-4 text-center opacity-0 animate-entrance-pop delay-[1000ms]">
                            <a href="/login" class="inline-block text-brand-primary hover:text-brand-primary-hover hover:underline transition-colors font-semibold text-sm">Employee Here!</a>
                        </div>
                    </form>

                    <div class="text-center mt-8 text-sm opacity-0 animate-entrance-pop delay-[1000ms]">
                        <p class="text-gray-500">&copy; 2026 Microfinance Logistics. All Rights Reserved.</p>
                    </div>
                </div>

                <!-- Open Vendor Opportunities Button -->
                <div class="w-full max-w-md mt-4 opacity-0 animate-entrance-up delay-[1200ms]">
                    <button type="button" id="opportunitiesBtn" 
                        class="w-full bg-amber-400 hover:bg-amber-500 text-amber-950 font-bold py-3 px-4 rounded-lg
                                transition-all duration-300 shadow-lg hover:shadow-amber-400/20
                                transform active:translate-y-0 active:scale-[0.99] flex items-center justify-center gap-2">
                        <i class='bx bx-briefcase-alt-2 text-xl'></i>
                        <span>Open Vendor Opportunities</span>
                    </button>
                </div>
            </section>
        </div>
    </main>

    <!-- Vendor Opportunities Section -->
    <section id="opportunitiesSection" class="min-h-screen bg-gray-100 py-16 px-6 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
            <div class="absolute w-64 h-64 top-10 right-10 bg-brand-primary/20 rounded-full blur-3xl"></div>
            <div class="absolute w-96 h-96 bottom-10 left-10 bg-brand-primary/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-6xl mx-auto relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Vendor Opportunities</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Join our network of trusted partners. We are looking for reliable vendors to grow with us and provide exceptional value to our customers.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Opportunity Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-package text-3xl text-brand-primary'></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Product Supplies</h3>
                    <p class="text-gray-600 mb-6">Supply high-quality office equipment, furniture, and essential business materials.</p>
                    <button class="text-brand-primary font-bold flex items-center gap-2 group-hover:translate-x-2 transition-transform">
                        Apply Now <i class='bx bx-right-arrow-alt'></i>
                    </button>
                </div>

                <!-- Opportunity Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-wrench text-3xl text-blue-600'></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Maintenance Services</h3>
                    <p class="text-gray-600 mb-6">Provide expert facility maintenance, electrical, and technical support services.</p>
                    <button class="text-brand-primary font-bold flex items-center gap-2 group-hover:translate-x-2 transition-transform">
                        Apply Now <i class='bx bx-right-arrow-alt'></i>
                    </button>
                </div>

                <!-- Opportunity Card 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-purple-50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-laptop text-3xl text-purple-600'></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">IT Solutions</h3>
                    <p class="text-gray-600 mb-6">Partner with us for software licensing, hardware maintenance, and digital services.</p>
                    <button class="text-brand-primary font-bold flex items-center gap-2 group-hover:translate-x-2 transition-transform">
                        Apply Now <i class='bx bx-right-arrow-alt'></i>
                    </button>
                </div>
            </div>

            <!-- Registration Call to Action -->
            <div class="bg-brand-primary rounded-3xl p-10 text-white text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class='bx bxs-store-alt text-9xl'></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Ready to become an official vendor?</h3>
                <p class="text-white/80 mb-8 max-w-xl mx-auto">Fill out our comprehensive vendor registration form to start the evaluation process.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-brand-primary font-bold py-3 px-8 rounded-xl hover:bg-gray-100 transition-colors shadow-lg">
                        Start Application
                    </button>
                    <button class="bg-brand-primary-hover border border-white/30 text-white font-bold py-3 px-8 rounded-xl hover:bg-brand-primary transition-colors">
                        Download Guide
                    </button>
                </div>
            </div>

            <div class="mt-12 text-center">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-600 font-semibold rounded-full hover:bg-gray-50 hover:text-brand-primary hover:border-brand-primary/30 transition-all duration-300 shadow-sm hover:shadow-md active:scale-95">
                    <i class='bx bx-up-arrow-alt text-xl'></i>
                    Back to Login
                </button>
            </div>
        </div>
    </section>

    <div id="terms-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-purple-50 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class='bx bxs-file-doc text-purple-600 text-xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Vendor Terms & Conditions</h3>
                        <p class="text-sm text-gray-600">Please read and accept the terms to continue</p>
                    </div>
                </div>
                <button id="close-terms-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <div class="p-6 flex-1 overflow-y-auto">
                <div class="space-y-6">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Microfinancial Vendors Portal</h2>
                        <p class="text-gray-600">Terms of Service and Vendor Agreement</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class='bx bxs-calendar text-purple-600 mr-2'></i>
                            <span class="text-sm text-purple-800">Last Updated: December 2025</span>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">1. Acceptance of Terms</h4>
                            <p class="text-gray-700 text-sm leading-relaxed">By accessing and using the Microfinancial Vendors Portal, you agree to abide by these terms and policies.</p>
                        </div>
                        <div class="space-y-3">
                            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">2. Vendor Responsibilities</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p class="flex items-start"><i class='bx bxs-check-circle text-purple-600 mr-2 mt-0.5 flex-shrink-0'></i><span>Maintain confidentiality of your credentials.</span></p>
                                <p class="flex items-start"><i class='bx bxs-check-circle text-purple-600 mr-2 mt-0.5 flex-shrink-0'></i><span>Provide accurate vendor information and keep it updated.</span></p>
                                <p class="flex items-start"><i class='bx bxs-check-circle text-purple-600 mr-2 mt-0.5 flex-shrink-0'></i><span>Use the portal only for vendor-related activities.</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class='bx bxs-info-circle text-green-600'></i>
                    <span>By clicking "Yes, I Agree" you accept all terms and conditions</span>
                </div>
                <div class="flex space-x-3">
                    <button id="decline-terms" class="btn btn-ghost text-gray-600 hover:bg-gray-200">Cancel</button>
                    <button id="accept-terms" class="btn btn-primary border-none bg-green-600 hover:bg-green-700 text-white"><i class='bx bxs-check-circle mr-2'></i>Yes, I Agree</button>
                </div>
            </div>
        </div>
    </div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Vendor Login Script Loaded - v1.1 (Fixed Route)');
    handleLogin();
});

document.addEventListener('DOMContentLoaded', function() {
        const opportunitiesBtn = document.getElementById('opportunitiesBtn');
        const opportunitiesSection = document.getElementById('opportunitiesSection');
        
        if (opportunitiesBtn && opportunitiesSection) {
            opportunitiesBtn.addEventListener('click', function() {
                opportunitiesSection.scrollIntoView({ behavior: 'smooth' });
            });
        }

        const termsLink = document.getElementById('terms-link');
    const termsModal = document.getElementById('terms-modal');
    const closeTermsModal = document.getElementById('close-terms-modal');
    const declineTerms = document.getElementById('decline-terms');
    const acceptTerms = document.getElementById('accept-terms');
    const agreeCheckbox = document.getElementById('agree');
    termsLink.addEventListener('click', function(e) { e.preventDefault(); termsModal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; });
    function closeTermsModalFunc() { termsModal.classList.add('hidden'); document.body.style.overflow = ''; }
    closeTermsModal.addEventListener('click', closeTermsModalFunc);
    declineTerms.addEventListener('click', closeTermsModalFunc);
    acceptTerms.addEventListener('click', function() { agreeCheckbox.checked = true; agreeCheckbox.setAttribute('aria-checked', 'true'); closeTermsModalFunc(); const t = acceptTerms.innerHTML; acceptTerms.innerHTML = '<i class="bx bxs-check-circle mr-2"></i>Accepted!'; acceptTerms.disabled = true; setTimeout(() => { acceptTerms.innerHTML = t; acceptTerms.disabled = false; }, 1500); });
    termsModal.addEventListener('click', function(e) { if (e.target === termsModal) { closeTermsModalFunc(); } });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && !termsModal.classList.contains('hidden')) { closeTermsModalFunc(); } });
});

function handleLogin() {
    document.querySelectorAll('[id$="-error"]').forEach(el => { el.classList.add('hidden'); });
    const loginError = document.getElementById('login-error'); loginError.classList.add('hidden');
    const formData = new FormData(document.getElementById('loginForm'));
    const email = formData.get('email'); const password = formData.get('password'); const agree = document.getElementById('agree').checked;
    let hasError = false;
    if (!email) { showError('email-error', 'Email is required'); hasError = true; }
    if (!password) { showError('password-error', 'Password is required'); hasError = true; }
    if (!agree) { showError('agree-error', 'You must agree to the terms and conditions'); hasError = true; }
    if (hasError) { return; }
    const loginBtn = document.getElementById('loginBtn'); const loginText = document.getElementById('loginText'); const loginSpinner = document.getElementById('loginSpinner');
    loginBtn.disabled = true; loginText.textContent = 'Logging in...'; loginSpinner.classList.remove('hidden');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/api/v1/vendor/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
        credentials: 'include',
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => { 
        if (!response.ok) { 
            return response.json().then(errorData => { 
                console.error('Vendor login error:', errorData);
                throw new Error(errorData.message || "Incorrect credential please try again!"); 
            }).catch(e => { 
                console.error('Vendor login parse error:', e);
                throw new Error("Incorrect credential please try again!"); 
            }); 
        } 
        return response.json(); 
    })
    .then(data => { 
        if (data.success) { 
            if (data.requires_otp) { 
                 // For debugging/local development: Show OTP if email fails
                 if (data.otp_debug) {
                     console.log('OTP Code:', data.otp_debug);
                 }
                window.location.href = `/otp-verification?portal=vendor&email=${encodeURIComponent(data.email)}`; 
            } else { 
                window.location.href = '/vendor/splash-login'; 
            } 
        } else { 
            throw new Error(data.message || 'Login failed. Please check your credentials and try again.'); 
        } 
    })
    .catch(error => { const loginError = document.getElementById('login-error'); const loginErrorMessage = document.getElementById('login-error-message'); loginErrorMessage.textContent = error.message; loginError.classList.remove('hidden'); loginError.scrollIntoView({ behavior: 'smooth', block: 'center' }); setTimeout(() => { loginError.classList.add('hidden'); }, 8000); })
    .finally(() => { loginBtn.disabled = false; loginText.textContent = 'Login'; loginSpinner.classList.add('hidden'); });
}

function showError(id, message) { const el = document.getElementById(id); el.textContent = message; el.classList.remove('hidden'); }

function togglePassword(btn){ var input = document.getElementById('password'); var icon = btn.querySelector('i'); if (!input) return; if (input.type === 'password') { input.type = 'text'; icon.classList.remove('bx-show-alt'); icon.classList.add('bx-hide'); btn.setAttribute('aria-pressed', 'true'); btn.setAttribute('aria-label', 'Hide password'); } else { input.type = 'password'; icon.classList.remove('bx-hide'); icon.classList.add('bx-show-alt'); btn.setAttribute('aria-pressed', 'false'); btn.setAttribute('aria-label', 'Show password'); } }

console.log('Vendor login form loaded successfully');
</script>
</body>
</html>