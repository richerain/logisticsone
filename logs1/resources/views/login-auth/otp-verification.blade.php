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
            <!-- otp verification form panel start -->
            <div class="md:w-1/2 p-8">
                <h2 class="text-2xl font-bold text-gray-700 text-center">OTP Verification</h2>
                <p class="text-center text-gray-600 mb-6">
                    Enter the 6-digit code sent to
                    <strong class="text-gray-800" id="user-email">{{ request()->get('email', 'mail@site.com') }}</strong>.
                    Didn't receive it? You can resend after the timer expires.
                </p>

                <!-- otp form start -->
                <form id="otp-form" class="space-y-4" novalidate>
                    <!-- hidden email -->
                    <input type="hidden" name="email" id="email" value="{{ request()->get('email', 'mail@site.com') }}" />

                    <!-- OTP inputs (single row on all screen sizes) -->
                    <div class="grid grid-cols-6 gap-2 justify-items-center">
                        <input name="otp1" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 1" />
                        <input name="otp2" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 2" />
                        <input name="otp3" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 3" />
                        <input name="otp4" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 4" />
                        <input name="otp5" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 5" />
                        <input name="otp6" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 6" />
                    </div>

                    <!-- helper text / error -->
                    <p id="otp-error" class="text-center text-red-600 text-sm hidden">Please enter the full 6-digit code.</p>

                    <!-- resend / timer row -->
                    <div class="flex items-center justify-start">
                        <div class="text-sm text-gray-600">
                            <button id="resend-btn" type="button" class="text-blue-600 hover:underline disabled:opacity-50" disabled>Resend OTP</button>
                            <span id="timer" class="ml-2 text-gray-500">(01:00)</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <button id="verify-btn" type="button" class="btn btn-primary w-full">
                            <span id="verify-text">Verify OTP</span>
                            <span id="verify-spinner" class="loading loading-spinner loading-sm hidden"></span>
                        </button>
                        <button id="cancel-btn" type="button" class="btn btn-ghost w-full">Cancel</button>
                    </div>
                </form>
                <!-- otp form end -->

            </div>
            <!-- otp verification form end -->
        </div>
    </main>

    <script>
        class OTPHandler {
            constructor() {
                this.otpInputs = document.querySelectorAll('.otp');
                this.verifyBtn = document.getElementById('verify-btn');
                this.resendBtn = document.getElementById('resend-btn');
                this.cancelBtn = document.getElementById('cancel-btn');
                this.timerElement = document.getElementById('timer');
                this.email = document.getElementById('email').value;
                
                this.timer = 60;
                this.timerInterval = null;
                
                this.init();
            }
            
            init() {
                this.setupOTPInputs();
                this.setupEventListeners();
                this.startTimer();
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
                    const response = await fetch('/api/verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: this.email,
                            otp: otpCode
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Store token and redirect
                        localStorage.setItem('auth_token', data.token);
                        window.location.href = '/splash-login';
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
                    const response = await fetch('/api/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
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
                            text: 'A new OTP has been sent to your email'
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
                window.location.href = '/login';
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
                this.verifyBtn.disabled = true;
            }
        }
        
        // Initialize OTP handler when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new OTPHandler();
        });
    </script>
</body>
</html>