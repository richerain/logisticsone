<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verification</title>
  <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .animate-slideIn {
      animation: slideIn 0.3s ease-out;
    }
    .otp-input {
      width: 50px;
      height: 50px;
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
      border: 2px solid #d1d5db;
      border-radius: 10px;
      margin: 0 5px;
    }
    .otp-input:focus {
      border-color: #16a34a;
      box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }
  </style>
</head>
<body class="bg-green-700 font-inter min-h-screen flex overflow-hidden">
  <div class="container flex flex-row w-full m-10 bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Section 1: Branding -->
    <div class="section-1 w-3/5 flex justify-center items-center bg-black">
      <div class="company-content text-center w-full">
        <div class="company-logo flex justify-center items-center">
          <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Logo" class="w-96 h-auto opacity-100 filter drop-shadow-lg">
        </div>
        <div class="company-text uppercase">
          <h1 class="text-5xl font-extrabold bg-green-500 bg-clip-text text-transparent mb-2">Microfinancial</h1>
          <p class="text-2xl text-green-600 font-medium">Logistics I Department</p>
        </div>
      </div>
    </div>

    <!-- Section 2: OTP Verification Form -->
    <div class="section-2 w-2/5 flex justify-center items-center bg-white p-8">
      <div class="otp-card w-full max-w-full">
        <div class="card-body p-8"> 
          <!-- Header Section -->
          <div class="text-center mb-6">
            <div class="otp-text text-2xl font-bold bg-gradient-to-r from-gray-700 to-gray-600 bg-clip-text text-transparent">OTP Verification</div>
            <div class="otp-subtitle text-gray-500 text-sm font-medium mt-2">
              Enter the 6-digit code sent to your email
            </div>
            <div id="userEmail" class="text-green-600 font-semibold text-lg mt-2"></div>
          </div>

          <!-- Error Alert -->
          <div id="otpErrorAlert" class="custom-otp-alert hidden items-center border-2 border-dotted border-red-100 rounded-xl p-4 bg-red-50 animate-slideIn text-sm overflow-hidden">
            <i class="bx bx-fw bx-x-circle text-red-500 shrink-0 mr-2"></i>
            <span id="otpErrorMessage" class="flex-1">Invalid OTP code.</span>
          </div>

          <!-- Success Alert -->
          <div id="otpSuccessAlert" class="custom-otp-alert hidden items-center border-2 border-dotted border-green-100 rounded-xl p-4 bg-green-50 animate-slideIn text-sm overflow-hidden">
            <i class="bx bx-fw bx-check-circle text-green-500 shrink-0 mr-2"></i>
            <span id="otpSuccessMessage" class="flex-1">OTP sent successfully!</span>
          </div>

          <form id="otpForm" class="space-y-6">
            <!-- OTP Input -->
            <div class="form-control">
              <div class="flex justify-between items-center mb-4">
                <label class="form-label text-gray-700 font-semibold text-sm">6-Digit Code</label>
                <div class="error-message text-red-500 text-sm font-medium items-center gap-2 hidden" id="otpError">
                  <i class='bx bx-error-circle'></i>
                  <span>Please enter all 6 digits</span>
                </div>
              </div>
              <div class="flex justify-center space-x-2 mb-4" id="otpContainer">
                <!-- OTP inputs will be generated here -->
              </div>
            </div>

            <!-- Timer and Resend -->
            <div class="text-center">
              <div id="resendSection" class="hidden">
                <p class="text-gray-600 text-sm">Didn't receive the code?</p>
                <button type="button" id="resendOtp" class="text-green-600 font-semibold hover:text-green-700 transition-colors duration-300">
                  Resend OTP
                </button>
              </div>
              <div id="timerSection" class="text-gray-600 text-sm">
                Resend available in <span id="countdown">60</span> seconds
              </div>
            </div>

            <!-- Verify Button -->
            <div class="form-control mt-8">
              <button type="submit" class="btn bg-green-600 text-white border-none font-semibold text-base h-14 w-full transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none" id="verifyButton">
                Verify OTP
              </button>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
              <button type="button" id="backToLogin" class="text-gray-600 hover:text-gray-800 transition-colors duration-300">
                <i class='bx bx-arrow-back mr-2'></i>Back to Login
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script>
    let otpSessionId = '';
    let userEmail = '';

    // Initialize OTP inputs
    function initializeOtpInputs() {
        const otpContainer = document.getElementById('otpContainer');
        otpContainer.innerHTML = '';
        
        for (let i = 0; i < 6; i++) {
            const input = document.createElement('input');
            input.type = 'text';
            input.maxLength = 1;
            input.className = 'otp-input';
            input.dataset.index = i;
            
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                if (value.length === 1) {
                    // Move to next input
                    const nextIndex = parseInt(e.target.dataset.index) + 1;
                    const nextInput = document.querySelector(`.otp-input[data-index="${nextIndex}"]`);
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '') {
                    // Move to previous input
                    const prevIndex = parseInt(e.target.dataset.index) - 1;
                    const prevInput = document.querySelector(`.otp-input[data-index="${prevIndex}"]`);
                    if (prevInput) {
                        prevInput.focus();
                    }
                }
            });
            
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6);
                const inputs = document.querySelectorAll('.otp-input');
                
                pasteData.split('').forEach((char, index) => {
                    if (inputs[index]) {
                        inputs[index].value = char;
                    }
                });
                
                // Focus last input
                if (inputs[pasteData.length - 1]) {
                    inputs[pasteData.length - 1].focus();
                }
            });
            
            otpContainer.appendChild(input);
        }
    }

    // Get OTP code from inputs
    function getOtpCode() {
        const inputs = document.querySelectorAll('.otp-input');
        return Array.from(inputs).map(input => input.value).join('');
    }

    // Show error alert
    function showOtpError(message) {
        const alert = document.getElementById('otpErrorAlert');
        const messageSpan = document.getElementById('otpErrorMessage');
        messageSpan.textContent = message;
        alert.classList.remove('hidden');
        alert.classList.add('flex');
        
        setTimeout(() => {
            hideOtpError();
        }, 5000);
    }

    function hideOtpError() {
        const alert = document.getElementById('otpErrorAlert');
        alert.classList.add('hidden');
        alert.classList.remove('flex');
    }

    // Show success alert
    function showOtpSuccess(message) {
        const alert = document.getElementById('otpSuccessAlert');
        const messageSpan = document.getElementById('otpSuccessMessage');
        messageSpan.textContent = message;
        alert.classList.remove('hidden');
        alert.classList.add('flex');
        
        setTimeout(() => {
            hideOtpSuccess();
        }, 3000);
    }

    function hideOtpSuccess() {
        const alert = document.getElementById('otpSuccessAlert');
        alert.classList.add('hidden');
        alert.classList.remove('flex');
    }

    // Timer functionality
    function startTimer(duration) {
        const timerElement = document.getElementById('countdown');
        const resendSection = document.getElementById('resendSection');
        const timerSection = document.getElementById('timerSection');
        
        let timeLeft = duration;
        
        const timer = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                timerSection.classList.add('hidden');
                resendSection.classList.remove('hidden');
            }
        }, 1000);
    }

    // Resend OTP
    document.getElementById('resendOtp').addEventListener('click', async function() {
        try {
            const response = await fetch('/api/auth/resend-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    session_id: otpSessionId,
                    email: userEmail
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showOtpSuccess('New OTP sent to your email!');
                // Reset timer
                document.getElementById('resendSection').classList.add('hidden');
                document.getElementById('timerSection').classList.remove('hidden');
                startTimer(60);
            } else {
                showOtpError(data.message || 'Failed to resend OTP');
            }
        } catch (error) {
            showOtpError('Failed to resend OTP. Please try again.');
        }
    });

    // Back to login
    document.getElementById('backToLogin').addEventListener('click', function() {
        window.location.href = '/login';
    });

    // OTP form submission
    document.getElementById('otpForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const otpCode = getOtpCode();
        
        if (otpCode.length !== 6) {
            showOtpError('Please enter all 6 digits of the OTP code');
            return;
        }

        // Show loading
        const verifyButton = document.getElementById('verifyButton');
        verifyButton.disabled = true;
        verifyButton.innerHTML = 'Verifying... <i class="bx bx-loader-alt bx-spin ml-2"></i>';

        try {
            const response = await fetch('/api/auth/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    session_id: otpSessionId,
                    otp_code: otpCode,
                    email: userEmail
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Store authentication data
                localStorage.setItem('isAuthenticated', 'true');
                localStorage.setItem('user', JSON.stringify(data.user));
                localStorage.setItem('lastActivity', Date.now().toString());
                
                // Set cookies
                document.cookie = `isAuthenticated=true; path=/; max-age=${24 * 60 * 60}`;
                document.cookie = `user=${encodeURIComponent(JSON.stringify(data.user))}; path=/; max-age=${24 * 60 * 60}`;
                document.cookie = `lastActivity=${Date.now()}; path=/; max-age=${24 * 60 * 60}`;

                Swal.fire({
                    icon: 'success',
                    title: 'Verification Successful!',
                    text: 'Redirecting to Logistics System...',
                    timer: 1500,
                    showConfirmButton: false,
                    background: '#f0fdf4',
                    color: '#065f46'
                }).then(() => {
                    // Redirect to login-splash first, then it will auto-redirect to dashboard
                    window.location.href = '/login-splash';
                });
            } else {
                throw new Error(data.message || 'Invalid OTP code');
            }
        } catch (error) {
            showOtpError(error.message || 'Verification failed. Please try again.');
        } finally {
            verifyButton.disabled = false;
            verifyButton.innerHTML = 'Verify OTP';
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeOtpInputs();
        
        // Get session data from localStorage
        otpSessionId = localStorage.getItem('otpSessionId');
        userEmail = localStorage.getItem('otpUserEmail');
        
        if (userEmail) {
            document.getElementById('userEmail').textContent = userEmail;
        } else {
            // No OTP session found, redirect to login
            window.location.href = '/login';
        }
        
        // Start the timer
        startTimer(60);
        
        // Focus first OTP input
        document.querySelector('.otp-input[data-index="0"]')?.focus();
    });
</script>
</body>
</html>