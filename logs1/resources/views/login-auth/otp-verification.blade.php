<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}" />
    <title>OTP Verification</title>
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
                        <h1 class="text-3xl font-bold mt-4">{{ (request()->get('portal') === 'vendor') ? 'Microfinancial Vendors' : 'Microfinancial Logistics' }}</h1>
                        <p class="text-white/80">{{ (request()->get('portal') === 'vendor') ? 'Vendor Portal' : 'Logistics I' }}</p>
                    </div>

                    <!-- Illustration Carousel -->
                    <div class="relative w-full max-w-2xl h-64 my-4">
                        <img src="{{ asset('images/login-img/illustration-1.svg') }}" alt="Illustration 1" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-100">
                        <img src="{{ asset('images/login-img/illustration-2.svg') }}" alt="Illustration 2" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/login-img/illustration-3.svg') }}" alt="Illustration 3" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/login-img/illustration-4.svg') }}" alt="Illustration 4" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/login-img/illustration-5.svg') }}" alt="Illustration 5" class="login-svg absolute inset-0 w-full h-full object-contain transition-opacity duration-1000 opacity-0">
                    </div>

                    <div class="text-center mt-4 max-w-xl">
                        <p class="italic text-white/90 text-base leading-relaxed">
                            “The strength of the team is each individual member. The strength of each member is the team.”
                        </p>
                        <cite class="block text-right mt-2 text-white/60">- Phil Jackson</cite>
                    </div>
                </div>
            </section>
            
            <!-- Right Panel: OTP Form Card -->
            <section class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="bg-white/90 w-full max-w-md backdrop-blur-lg rounded-2xl shadow-2xl p-8">

                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-bold text-brand-text-primary">OTP Verification</h2>
                        <p class="text-brand-text-secondary mt-1">
                            Enter the 6-digit code sent to <strong class="text-brand-primary" id="user-email">{{ request()->get('email', 'mail@site.com') }}</strong>
                        </p>
                        <p class="text-xs text-brand-text-secondary mt-2">Didn't receive it? You can resend after the timer expires.</p>
                    </div>

                    <!-- otp form start -->
                    <form id="otp-form" class="space-y-6" novalidate>
                        @csrf
                        <!-- hidden email -->
                        <input type="hidden" name="email" id="email" value="{{ request()->get('email', 'mail@site.com') }}" />

                        <!-- OTP inputs (single row on all screen sizes) -->
                        <div class="grid grid-cols-6 gap-2 justify-items-center">
                            <input name="otp1" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" 
                                class="otp w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all duration-200" aria-label="Digit 1" />
                            <input name="otp2" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" 
                                class="otp w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all duration-200" aria-label="Digit 2" />
                            <input name="otp3" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" 
                                class="otp w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all duration-200" aria-label="Digit 3" />
                            <input name="otp4" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" 
                                class="otp w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all duration-200" aria-label="Digit 4" />
                            <input name="otp5" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" 
                                class="otp w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all duration-200" aria-label="Digit 5" />
                            <input name="otp6" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" 
                                class="otp w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary transition-all duration-200" aria-label="Digit 6" />
                        </div>

                        <!-- helper text / error -->
                        <p id="otp-error" class="text-center text-red-600 text-sm hidden">Please enter the full 6-digit code.</p>

                        <!-- resend / timer row -->
                        <div class="flex items-center justify-center">
                            <div class="text-sm text-gray-600">
                                <button id="resend-btn" type="button" class="text-brand-primary hover:text-brand-primary-hover hover:underline disabled:opacity-50 disabled:no-underline font-semibold" disabled>Resend OTP</button>
                                <span id="timer" class="ml-2 text-gray-500 font-mono">(01:00)</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button id="verify-btn" type="button" disabled
                                class="w-full bg-brand-primary text-white font-bold py-3 px-4 rounded-lg
                                        transition-all duration-300 shadow-lg
                                        transform active:translate-y-0 active:scale-[0.99]
                                        opacity-60 cursor-not-allowed">
                                <span id="verify-text">Verify OTP</span>
                                <span id="verify-spinner" class="loading loading-spinner loading-sm hidden"></span>
                            </button>
                            <button id="cancel-btn" type="button" class="w-full text-brand-text-secondary hover:text-brand-primary font-semibold py-2 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                    <!-- otp form end -->

                    <div class="text-center mt-8 text-sm">
                        <p class="text-gray-500">&copy; 2025 Microfinance Logistics. All Rights Reserved.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        // Carousel Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Carousel
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

            // Initialize OTP Handler
            new OTPHandler();
        });

        class OTPHandler {
            constructor() {
                this.otpInputs = document.querySelectorAll('.otp');
                this.verifyBtn = document.getElementById('verify-btn');
                this.resendBtn = document.getElementById('resend-btn');
                this.cancelBtn = document.getElementById('cancel-btn');
                this.timerElement = document.getElementById('timer');
                this.email = document.getElementById('email').value;
                this.portal = new URLSearchParams(window.location.search).get('portal') || 'employee';
                
                this.timer = 60;
                this.timerInterval = null;
                
                this.init();
            }
            
            init() {
                this.setupOTPInputs();
                this.setupEventListeners();
                this.startTimer();
                this.otpInputs[0].focus();
            }
            
            setupOTPInputs() {
                this.otpInputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        const value = e.target.value;
                        
                        // Auto-tab to next input
                        if (value.length === 1 && index < this.otpInputs.length - 1) {
                            this.otpInputs[index + 1].focus();
                        }
                        
                        // Enable verify button if all inputs are filled
                        if (this.isOTPComplete()) {
                            this.verifyBtn.disabled = false;
                            this.verifyBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                            this.verifyBtn.classList.add('hover:bg-brand-primary-hover', 'active:translate-y-0', 'active:scale-[0.99]');
                        } else {
                            this.verifyBtn.disabled = true;
                            this.verifyBtn.classList.add('opacity-60', 'cursor-not-allowed');
                            this.verifyBtn.classList.remove('hover:bg-brand-primary-hover', 'active:translate-y-0', 'active:scale-[0.99]');
                        }
                    });
                    
                    input.addEventListener('keydown', (e) => {
                        // Handle backspace
                        if (e.key === 'Backspace' && !e.target.value && index > 0) {
                            this.otpInputs[index - 1].focus();
                        }
                    });
                    
                    // Prevent non-numeric input
                    input.addEventListener('keypress', (e) => {
                        if (!/^\d$/.test(e.key)) {
                            e.preventDefault();
                        }
                    });

                    // Handle paste event
                    input.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pasteData = e.clipboardData.getData('text').replace(/\D/g, '');
                        if (pasteData.length === 6) {
                            pasteData.split('').forEach((char, idx) => {
                                if (this.otpInputs[idx]) {
                                    this.otpInputs[idx].value = char;
                                }
                            });
                            if (this.isOTPComplete()) {
                                this.verifyBtn.disabled = false;
                                this.verifyBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                                this.verifyBtn.classList.add('hover:bg-brand-primary-hover', 'active:translate-y-0', 'active:scale-[0.99]');
                            }
                        }
                    });
                });
            }
            
            setupEventListeners() {
                this.verifyBtn.addEventListener('click', () => this.verifyOTP());
                this.resendBtn.addEventListener('click', () => this.resendOTP());
                this.cancelBtn.addEventListener('click', () => this.cancelVerification());
            }
            
            isOTPComplete() {
                return Array.from(this.otpInputs).every(input => input.value.length === 1);
            }
            
            getOTPCode() {
                return Array.from(this.otpInputs).map(input => input.value).join('');
            }
            
            async verifyOTP() {
                if (!this.isOTPComplete()) {
                    this.showError('Please enter the full 6-digit code');
                    return;
                }
                
                const otpCode = this.getOTPCode();
                
                // Show loading state
                this.setVerifyButtonLoading(true);
                
                try {
                    const response = await fetch(this.portal === 'vendor' ? '/api/vendor/verify-otp' : '/api/verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'include', // Ensure session cookies are handled
                        body: JSON.stringify({
                            email: this.email,
                            otp: otpCode
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        if (data.token) {
                            try {
                                localStorage.setItem('jwt', data.token);
                                if (data.expires_at) {
                                    localStorage.setItem('jwt_exp', data.expires_at);
                                }
                            } catch (e) {}
                        }
                        window.location.href = (this.portal === 'vendor') ? '/vendor/splash-login' : '/splash-login';
                    } else {
                        throw new Error(data.message || 'OTP verification failed');
                    }
                } catch (error) {
                    this.showError(error.message);
                    this.clearOTPInputs();
                } finally {
                    this.setVerifyButtonLoading(false);
                }
            }
            
            async resendOTP() {
                this.setResendButtonLoading(true);
                
                try {
                    const response = await fetch(this.portal === 'vendor' ? '/api/vendor/send-otp' : '/api/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: this.email
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Resent',
                            text: 'A new OTP has been sent to your email',
                            confirmButtonColor: '#059669'
                        });
                        
                        this.resetTimer();
                        this.clearOTPInputs();
                        this.otpInputs[0].focus();
                    } else {
                        throw new Error(data.message || 'Failed to resend OTP');
                    }
                } catch (error) {
                    this.showError(error.message);
                } finally {
                    this.setResendButtonLoading(false);
                }
            }
            
            cancelVerification() {
                if (this.portal === 'vendor') {
                    window.location.href = '/login/vendor-portal';
                } else {
                    window.location.href = '/login';
                }
            }
            
            startTimer() {
                this.timer = 60;
                this.updateTimerDisplay();
                
                this.timerInterval = setInterval(() => {
                    this.timer--;
                    this.updateTimerDisplay();
                    
                    if (this.timer <= 0) {
                        this.stopTimer();
                        this.resendBtn.disabled = false;
                    }
                }, 1000);
            }
            
            stopTimer() {
                if (this.timerInterval) {
                    clearInterval(this.timerInterval);
                    this.timerInterval = null;
                }
            }
            
            resetTimer() {
                this.stopTimer();
                this.startTimer();
                this.resendBtn.disabled = true;
            }
            
            updateTimerDisplay() {
                const minutes = Math.floor(this.timer / 60);
                const seconds = this.timer % 60;
                this.timerElement.textContent = `(${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')})`;
            }
            
            setVerifyButtonLoading(loading) {
                const verifyText = document.getElementById('verify-text');
                const verifySpinner = document.getElementById('verify-spinner');
                
                this.verifyBtn.disabled = loading;
                
                if (loading) {
                    verifyText.textContent = 'Verifying...';
                    verifySpinner.classList.remove('hidden');
                } else {
                    verifyText.textContent = 'Verify OTP';
                    verifySpinner.classList.add('hidden');
                }
            }
            
            setResendButtonLoading(loading) {
                if (loading) {
                    this.resendBtn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Sending...';
                } else {
                    this.resendBtn.textContent = 'Resend OTP';
                }
            }
            
            showError(message) {
                const errorElement = document.getElementById('otp-error');
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                
                // Auto-hide error after 5 seconds
                setTimeout(() => {
                    errorElement.classList.add('hidden');
                }, 5000);
            }
            
            clearOTPInputs() {
                this.otpInputs.forEach(input => {
                    input.value = '';
                });
                this.otpInputs[0].focus();
                
                // Disable button
                this.verifyBtn.disabled = true;
                this.verifyBtn.classList.add('opacity-60', 'cursor-not-allowed');
                this.verifyBtn.classList.remove('hover:bg-brand-primary-hover', 'active:translate-y-0', 'active:scale-[0.99]');
            }
        }
    </script>
</body>
</html>