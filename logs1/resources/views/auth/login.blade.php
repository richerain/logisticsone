<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
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

    <!-- Section 2: Login Form -->
    <div class="section-2 w-2/5 flex justify-center items-center bg-white p-8">
      <div class="login-card w-full max-w-full">
        <div class="card-body p-8"> 
          <!-- Header Section -->
          <div class="text-center">
            <div class="welcome-text text-2xl font-bold bg-gradient-to-r from-gray-700 to-gray-600 bg-clip-text text-transparent">Welcome to Login</div>
            <div class="welcome-subtitle text-gray-500 text-sm font-medium">Sign in to access your account</div>
          </div>

          <!-- Error Alert -->
          <div id="loginErrorAlert" class="custom-login-alert hidden items-center border-2 border-dotted border-red-100 rounded-xl p-4 bg-red-50 animate-slideIn text-sm overflow-hidden h-0">
            <i class="bx bx-fw bx-x-circle text-red-500 shrink-0 mr-2"></i>
            <span id="loginErrorMessage" class="flex-1">Invalid email or password.</span>
          </div>

          <form id="loginForm" class="space-y-6">
            <!-- Email Input -->
            <div class="form-control">
              <div class="flex justify-between items-center mb-2">
                <label class="form-label text-gray-700 font-semibold text-sm">Email</label>
                <div class="error-message text-red-500 text-sm font-medium items-center gap-2 hidden" id="emailError">
                  <i class='bx bx-error-circle'></i>
                  <span>The email is required</span>
                </div>
              </div>
              <div class="relative">
                <input type="email" id="email" name="email" placeholder="Enter your email" class="form-input bg-gray-50 border-2 border-gray-200 rounded-xl pl-12 pr-4 h-14 w-full text-base transition-all duration-300 focus:bg-white focus:border-green-500 focus:ring-3 focus:ring-green-100 hover:border-gray-300">
                <i class='bx bxs-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg transition-colors duration-300'></i>
              </div>
            </div>

            <!-- Password Input -->
            <div class="form-control">
              <div class="flex justify-between items-center mb-2">
                <label class="form-label text-gray-700 font-semibold text-sm">Password</label>
                <div class="error-message text-red-500 text-sm font-medium items-center gap-2 hidden" id="passwordError">
                  <i class='bx bx-error-circle'></i>
                  <span>The password is required</span>
                </div>
              </div>
              <div class="relative">
                <input type="password" id="password" name="password" placeholder="Enter your password" class="form-input bg-gray-50 border-2 border-gray-200 rounded-xl pl-12 pr-12 h-14 w-full text-base transition-all duration-300 focus:bg-white focus:border-green-500 focus:ring-3 focus:ring-green-100 hover:border-gray-300">
                <i class='bx bxs-lock-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg transition-colors duration-300'></i>
                <i class='bx bx-show absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg cursor-pointer transition-colors duration-300' id="togglePassword"></i>
              </div>
            </div>

            <!-- Login Button -->
            <div class="form-control mt-8">
              <button type="submit" class="btn bg-green-600 text-white border-none font-semibold text-base h-14 w-full transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none" id="loginButton" disabled>
                Sign In
              </button>
            </div>

            <!-- Terms and Conditions Checkbox -->
            <div class="terms-checkbox-container mt-6 p-4 rounded-xl">
              <div class="checkbox-wrapper flex items-start gap-3">
                <input type="checkbox" id="termsCheckbox" class="checkbox-input w-4 h-4 border-2 border-gray-300 rounded bg-white cursor-pointer mt-0.5 transition-all duration-300 checked:bg-green-500 checked:border-green-500">
                <label for="termsCheckbox" class="checkbox-label text-gray-700 text-sm leading-relaxed cursor-pointer select-none">
                  I have read and agree to the <span class="terms-link text-green-500 font-medium cursor-pointer no-underline hover:text-green-600 hover:underline transition-colors duration-300" id="termsLink">Terms and Conditions.</span>
                </label>
              </div>
              <div class="checkbox-error text-red-500 text-xs mt-2 items-center gap-2 hidden" id="termsError">
                <i class='bx bx-error-circle'></i>
                <span>You must accept the Terms and Conditions to sign in</span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div class="modal-overlay fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 opacity-0 invisible transition-all duration-300" id="termsModal">
    <div class="terms-modal bg-white rounded-2xl shadow-2xl w-11/12 max-w-4xl max-h-[80vh] overflow-hidden transform scale-90 transition-transform duration-300 flex flex-col">
      <div class="modal-header bg-green-600 text-white px-8 py-4 flex items-center gap-3 flex-shrink-0">
        <i class='bx bx-check-shield text-xl'></i>
        <h2 class="text-xl font-bold m-0">Terms and Conditions</h2>
        <button class="close-modal bg-transparent border-none text-white text-xl cursor-pointer p-1 rounded ml-auto" id="closeModal">
          <i class='bx bx-x'></i>
        </button>
      </div>
      <div class="modal-content p-8 overflow-y-auto flex-1">
        <!-- Terms content remains the same -->
        <div class="terms-section mb-6">
          <h3 class="text-gray-700 text-base font-semibold mb-2 flex items-center gap-2"><i class='bx bx-user-check text-green-500 text-lg'></i> Acceptance of Terms</h3>
          <p class="text-gray-500 leading-relaxed text-sm mb-3">By accessing and using the Microfinancial Logistics I System, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p>
          <p class="text-gray-500 leading-relaxed text-sm">Your continued use of the system constitutes acceptance of any modifications or updates to these terms.</p>
        </div>

        <div class="terms-section mb-6">
          <h3 class="text-gray-700 text-base font-semibold mb-2 flex items-center gap-2"><i class='bx bx-shield-alt-2 text-green-500 text-lg'></i> User Responsibilities</h3>
          <p class="text-gray-500 leading-relaxed text-sm mb-2">As an authorized user of this system, you are responsible for:</p>
          <ul class="text-gray-500 leading-relaxed text-sm pl-6">
            <li class="mb-1">Maintaining the confidentiality and security of your login credentials</li>
            <li class="mb-1">All activities and transactions performed under your account</li>
            <li class="mb-1">Ensuring the accuracy and completeness of information provided</li>
            <li class="mb-1">Complying with all company policies and applicable regulations</li>
            <li>Immediately reporting any suspicious activity or security breaches</li>
          </ul>
        </div>

        <div class="terms-section mb-6">
          <h3 class="text-gray-700 text-base font-semibold mb-2 flex items-center gap-2"><i class='bx bx-lock-alt text-green-500 text-lg'></i> Data Privacy and Security</h3>
          <p class="text-gray-500 leading-relaxed text-sm mb-2">Microfinancial is committed to protecting your privacy and securing sensitive information:</p>
          <ul class="text-gray-500 leading-relaxed text-sm pl-6">
            <li class="mb-1">We collect only necessary personal and operational data required for system functionality</li>
            <li class="mb-1">Industry-standard encryption protocols protect data during transmission and storage</li>
            <li class="mb-1">Regular security audits and monitoring systems are in place</li>
            <li class="mb-1">Access to sensitive information is strictly role-based and monitored</li>
            <li>We comply with all applicable data protection and privacy regulations</li>
          </ul>
        </div>

        <div class="terms-section mb-6">
          <h3 class="text-gray-700 text-base font-semibold mb-2 flex items-center gap-2"><i class='bx bx-cog text-green-500 text-lg'></i> System Usage Guidelines</h3>
          <p class="text-gray-500 leading-relaxed text-sm mb-2 font-semibold">Permitted Activities:</p>
          <ul class="text-gray-500 leading-relaxed text-sm pl-6 mb-3">
            <li class="mb-1">Access authorized modules/submodules based on your assigned roles</li>
            <li class="mb-1">Process legitimate logistics and financial transactions</li>
            <li class="mb-1">Generate and export reports for business purposes</li>
            <li class="mb-1">Collaborate with other authorized system users</li>
          </ul>
          <p class="text-gray-500 leading-relaxed text-sm mb-2 font-semibold">Strictly Prohibited:</p>
          <ul class="text-gray-500 leading-relaxed text-sm pl-6">
            <li class="mb-1">Unauthorized access to other users' data or restricted system areas</li>
            <li class="mb-1">Attempting to breach, test, or circumvent system security measures</li>
            <li class="mb-1">Sharing, lending, or transferring your login credentials to others</li>
            <li class="mb-1">Using the system for any illegal, fraudulent, or unauthorized purposes</li>
            <li>Introducing malicious code, viruses, or harmful components</li>
          </ul>
        </div>

        <div class="terms-section mb-6">
          <h3 class="text-gray-700 text-base font-semibold mb-2 flex items-center gap-2"><i class='bx bx-time text-green-500 text-lg'></i> System Availability and Maintenance</h3>
          <p class="text-gray-500 leading-relaxed text-sm mb-2">The system is designed for high availability, but may experience temporary unavailability during:</p>
          <ul class="text-gray-500 leading-relaxed text-sm pl-6">
            <li class="mb-1">Planned maintenance windows (typically announced in advance)</li>
            <li class="mb-1">Emergency security updates and patches</li>
            <li class="mb-1">Infrastructure upgrades and improvements</li>
            <li>Unforeseen technical issues or force majeure events</li>
          </ul>
        </div>

        <div class="terms-section">
          <h3 class="text-gray-700 text-base font-semibold mb-2 flex items-center gap-2"><i class='bx bx-revision text-green-500 text-lg'></i> Terms Modification</h3>
          <p class="text-gray-500 leading-relaxed text-sm">Microfinancial reserves the right to modify these Terms and Conditions at any time. Users will be notified of significant changes through system notifications or email. Continued use of the system after modifications constitutes acceptance of the updated terms.</p>
        </div>
      </div>
      <div class="modal-footer px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-end flex-shrink-0">
        <button class="btn-primary bg-green-600 text-white px-6 py-3 rounded-lg font-semibold cursor-pointer border-none transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg" id="agreeButton">
          I Understand and Agree
        </button>
      </div>
    </div>
  </div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bx-show');
        this.classList.toggle('bxs-hide');
    });

    // Terms and Conditions Checkbox Functionality
    const termsCheckbox = document.getElementById('termsCheckbox');
    const loginButton = document.getElementById('loginButton');
    const termsError = document.getElementById('termsError');
    const termsLink = document.getElementById('termsLink');

    // Prevent label from checking the checkbox
    const checkboxLabel = document.querySelector('.checkbox-label');
    checkboxLabel.addEventListener('click', function(event) {
        event.preventDefault();
        // Only open modal when clicking the link text, not the entire label
        if (event.target === termsLink || event.target.parentElement === termsLink) {
            openTermsModal();
        }
    });

    // Checkbox change handler
    termsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            loginButton.disabled = false;
            termsError.classList.add('hidden');
        } else {
            loginButton.disabled = true;
        }
    });

    function showErrorAlert(message) {
        const alert = document.getElementById('loginErrorAlert');
        const messageSpan = document.getElementById('loginErrorMessage');
        messageSpan.textContent = message;
        alert.classList.remove('hidden');
        alert.classList.add('flex');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            hideErrorAlert();
        }, 5000);
    }

    function hideErrorAlert() {
        const alert = document.getElementById('loginErrorAlert');
        alert.classList.add('hidden');
        alert.classList.remove('flex');
    }

    // Terms and Conditions Modal Functions
    function openTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');
        document.body.style.overflow = 'hidden';
        
        // Also activate the modal content
        const modalContent = modal.querySelector('.terms-modal');
        modalContent.classList.remove('scale-90');
        modalContent.classList.add('scale-100');
    }

    function closeTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.classList.remove('opacity-100', 'visible');
        modal.classList.add('opacity-0', 'invisible');
        document.body.style.overflow = 'auto';
        
        // Also deactivate the modal content
        const modalContent = modal.querySelector('.terms-modal');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-90');
    }

    // Function to check the terms checkbox (only called by agree button)
    function acceptTermsAndConditions() {
        termsCheckbox.checked = true;
        termsCheckbox.dispatchEvent(new Event('change'));
        closeTermsModal();
    }

    // Event listeners for terms modal
    termsLink.addEventListener('click', function(event) {
        event.preventDefault();
        openTermsModal();
    });
    
    document.getElementById('closeModal').addEventListener('click', closeTermsModal);
    document.getElementById('agreeButton').addEventListener('click', acceptTermsAndConditions);

    // Close modal when clicking outside the modal content
    document.getElementById('termsModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeTermsModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTermsModal();
        }
    });

    // Add input event listeners to remove error styling when user starts typing
    document.getElementById('email').addEventListener('input', function() {
        this.classList.remove('border-red-500', 'bg-red-50');
        document.getElementById('emailError').classList.add('hidden');
    });

    document.getElementById('password').addEventListener('input', function() {
        this.classList.remove('border-red-500', 'bg-red-50');
        document.getElementById('passwordError').classList.add('hidden');
    });

    async function handleLogin(event) {
        event.preventDefault();
        
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const termsAccepted = termsCheckbox.checked;

        // Clear previous errors
        hideErrorAlert();
        document.getElementById('emailError').classList.add('hidden');
        document.getElementById('passwordError').classList.add('hidden');
        document.getElementById('email').classList.remove('border-red-500', 'bg-red-50');
        document.getElementById('password').classList.remove('border-red-500', 'bg-red-50');
        termsError.classList.add('hidden');

        // Basic validation
        let hasError = false;
        if (!email) {
            document.getElementById('emailError').classList.remove('hidden');
            document.getElementById('email').classList.add('border-red-500', 'bg-red-50');
            hasError = true;
        }
        if (!password) {
            document.getElementById('passwordError').classList.remove('hidden');
            document.getElementById('password').classList.add('border-red-500', 'bg-red-50');
            hasError = true;
        }
        if (!termsAccepted) {
            termsError.classList.remove('hidden');
            hasError = true;
        }
        
        if (hasError) {
            return;
        }

        // Show loading alert
        Swal.fire({
            title: 'Verifying Credentials',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            // Call the FrontendController processLogin method which will proxy to gateway
            const response = await fetch('/process-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                if (data.requires_otp) {
                    Swal.close();
                    
                    // Store OTP session data
                    localStorage.setItem('otpSessionId', data.session_id);
                    localStorage.setItem('otpUserEmail', email);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'OTP Sent!',
                        html: 'We\'ve sent a 6-digit verification code to:<br><strong>' + email + '</strong>',
                        timer: 3000,
                        showConfirmButton: false,
                        background: '#f0fdf4',
                        color: '#065f46'
                    }).then(() => {
                        // Redirect to OTP verification page
                        window.location.href = '/otp-verification';
                    });
                } else {
                    // Direct login without OTP (fallback)
                    localStorage.setItem('isAuthenticated', 'true');
                    localStorage.setItem('user', JSON.stringify(data.user));
                    localStorage.setItem('lastActivity', Date.now().toString());
                    
                    document.cookie = `isAuthenticated=true; path=/; max-age=${24 * 60 * 60}`;
                    document.cookie = `user=${encodeURIComponent(JSON.stringify(data.user))}; path=/; max-age=${24 * 60 * 60}`;
                    document.cookie = `lastActivity=${Date.now()}; path=/; max-age=${24 * 60 * 60}`;

                    Swal.fire({
                        icon: 'success',
                        title: 'Access Granted!',
                        text: 'Welcome back! Redirecting to Logistics System...',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#f0fdf4',
                        color: '#065f46'
                    }).then(() => {
                        window.location.href = '/login-splash';
                    });
                }
            } else {
                throw new Error(data.message || 'Invalid email or password.');
            }
        } catch (error) {
            Swal.close(); // Close the loading alert
            showErrorAlert(error.message || 'Invalid email or password. Please check your credentials and try again.');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', handleLogin);

    // FIXED: Remove the automatic redirect logic that was causing constant refreshing
    // Only check authentication status without automatic redirects
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Login page loaded - authentication check disabled to prevent refreshing');
        
        // Optional: Just log the current auth status for debugging
        const isAuthenticated = localStorage.getItem('isAuthenticated') === 'true';
        const hasUser = localStorage.getItem('user');
        
        console.log('Current auth status:', {
            isAuthenticated: isAuthenticated,
            hasUser: !!hasUser,
            shouldStayOnLogin: true // We always stay on login page until manual login
        });
    });
</script>
</body>
</html>