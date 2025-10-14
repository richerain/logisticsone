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
    body {
      background: linear-gradient(135deg, #2f855a, #48bb78, #38a169);
      font-family: 'Inter', Arial, sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      overflow: hidden;
    }
    .container {
      display: flex;
      flex-direction: row;
      width: 100%;
      min-height: 100vh;
    }
    .section-1 {
      width: 60%;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }
    .section-2 {
      width: 40%;
      background: white;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin-right: 0;
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      font-family: 'Inter', Arial, sans-serif;
      padding: 2rem;
      box-sizing: border-box;
    }
    .company-content {
      position: relative;
      text-align: center;
      z-index: 1;
      width: 100%;
    }
    .company-logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 2rem;
    }
    .company-logo img {
      width: 320px;
      height: auto;
      opacity: 1;
      filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
    }
    .company-text h1 {
      font-size: 2.5rem;
      font-weight: 800;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
    }
    .company-text p {
      font-size: 1.25rem;
      color: #374151;
      font-weight: 500;
    }
    .login-card {
      width: 100%;
      max-width: 420px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      border: 1px solid #e5e7eb;
      overflow: hidden;
    }
    .btn-green {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      border: none;
      font-weight: 600;
      font-size: 1rem;
      height: 3.5rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-green:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }
    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      z-index: 10;
      transition: color 0.3s ease;
    }
    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      cursor: pointer;
      z-index: 10;
      transition: color 0.3s ease;
    }
    .input:focus ~ .input-icon,
    .input:focus ~ .password-toggle {
      color: #10b981;
    }
    .form-input {
      background: #f9fafb;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      padding-left: 3rem;
      padding-right: 3rem;
      height: 3.5rem;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    .form-input:focus {
      background: white;
      border-color: #10b981;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .form-input:hover {
      border-color: #d1d5db;
    }
    .error-message {
      color: #ef4444;
      font-size: 0.875rem;
      margin-top: 0.5rem;
      display: none;
      font-weight: 500;
      align-items: center;
      gap: 0.5rem;
    }
    .input-error {
      border-color: #ef4444 !important;
      background-color: #fef2f2;
    }
    .input-error:focus {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    /* Custom alert styles */
    .custom-login-alert {
      border-left: 4px solid #ef4444;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      animation: slideIn 0.3s ease-out;
    }
    .custom-login-alert i {
      color: #ef4444;
      font-size: 1.5rem;
      flex-shrink: 0;
    }
    .custom-login-alert span {
      color: #7f1d1d;
      font-size: 0.9rem;
      font-weight: 500;
      line-height: 1.4;
    }
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
    .welcome-text {
      background: linear-gradient(135deg, #374151, #6b7280);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    .welcome-subtitle {
      color: #9ca3af;
      font-size: 0.9rem;
      margin-bottom: 1rem;
      font-weight: 500;
    }
    .form-label {
      color: #374151;
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }
    .card-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
      margin: 1.5rem 0;
    }
    /* Terms and Conditions Link Styles */
    .terms-text {
      text-align: center;
      margin-top: 1.5rem;
      color: #6b7280;
      font-size: 0.875rem;
    }
    .terms-link {
      color: #10b981;
      text-decoration: none;
      font-weight: 500;
      cursor: pointer;
      transition: color 0.3s ease;
    }
    .terms-link:hover {
      color: #059669;
      text-decoration: underline;
    }
    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    .terms-modal {
      background: white;
      border-radius: 16px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      width: 90%;
      max-width: 700px;
      max-height: 80vh;
      overflow: hidden;
      transform: scale(0.9);
      transition: transform 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    .modal-overlay.active .terms-modal {
      transform: scale(1);
    }
    .modal-header {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-shrink: 0;
    }
    .modal-header h2 {
      font-size: 1.25rem;
      font-weight: 700;
      margin: 0;
    }
    .close-modal {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
      padding: 0.25rem;
      border-radius: 4px;
      margin-left: auto;
    }
    .modal-content {
      padding: 2rem;
      overflow-y: auto;
      flex: 1;
    }
    .terms-section {
      margin-bottom: 1.5rem;
    }
    .terms-section:last-child {
      margin-bottom: 0;
    }
    .terms-section h3 {
      color: #374151;
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .terms-section h3 i {
      color: #10b981;
      font-size: 1.125rem;
    }
    .terms-section p {
      color: #6b7280;
      line-height: 1.6;
      margin-bottom: 0.75rem;
      font-size: 0.9rem;
    }
    .terms-section ul {
      color: #6b7280;
      line-height: 1.6;
      padding-left: 1.5rem;
      font-size: 0.9rem;
    }
    .terms-section li {
      margin-bottom: 0.5rem;
    }
    .modal-footer {
      padding: 1rem 2rem;
      background-color: #f9fafb;
      border-top: 1px solid #e5e7eb;
      display: flex;
      justify-content: flex-end;
      flex-shrink: 0;
    }
    .btn-primary {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
    }
    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="section-1">
      <div class="company-content ml-4">
        <div class="company-logo">
          <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Logo">
        </div>
        <div class="company-text uppercase">
          <h1 class="bg-gray-800">Microfinancial</h1>
          <p>Logistics I Department</p>
        </div>
      </div>
    </div>

    <div class="section-2">
      <div class="login-card">
        <div class="card-body p-8"> 
          <!-- Header Section -->
          <div class="text-center mb-1">
            <div class="welcome-text">Welcome to Logistics I</div>
            <div class="welcome-subtitle">Sign in to access your account</div>
          </div>

          <!-- Error Alert -->
          <div id="loginErrorAlert" class="custom-login-alert bg-red-100 hidden">
            <i class='bx bx-x-circle'></i>
            <span id="loginErrorMessage">Invalid email or password.</span>
          </div>

          <form id="loginForm" class="space-y-5">
            <!-- Email Input -->
            <div class="form-control">
              <label class="form-label">Email</label>
              <div class="relative">
                <input type="email" id="email" name="email" placeholder="Enter your email" class="form-input input-lg w-full">
                <i class='bx bxs-envelope input-icon text-lg'></i>
              </div>
              <div class="error-message" id="emailError">
                <i class='bx bx-error-circle'></i>
                <span>The email is required</span>
              </div>
            </div>

            <!-- Password Input -->
            <div class="form-control">
              <label class="form-label">Password</label>
              <div class="relative">
                <input type="password" id="password" name="password" placeholder="Enter your password" class="form-input input-lg w-full">
                <i class='bx bxs-lock-alt input-icon text-lg'></i>
                <i class='bx bx-show password-toggle text-lg' id="togglePassword"></i>
              </div>
              <div class="error-message" id="passwordError">
                <i class='bx bx-error-circle'></i>
                <span>The password is required</span>
              </div>
            </div>

            <!-- Login Button -->
            <div class="form-control mt-8">
              <button type="submit" class="btn btn-green btn-lg w-full">
                Sign In
              </button>
            </div>

            <!-- Terms and Conditions Text -->
            <div class="terms-text">
              By signing in you agree to our <span class="terms-link" id="termsLink">Terms and Conditions</span>.
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div class="modal-overlay" id="termsModal">
    <div class="terms-modal">
      <div class="modal-header">
        <i class='bx bxs-notepad text-xl'></i>
        <h2>Terms and Conditions</h2>
        <button class="close-modal" id="closeModal">
          <i class='bx bx-x'></i>
        </button>
      </div>
      <div class="modal-content">
        <div class="terms-section">
          <h3><i class='bx bx-user-check'></i> Acceptance of Terms</h3>
          <p>By accessing and using the Microfinancial Logistics I System, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</p>
          <p>Your continued use of the system constitutes acceptance of any modifications or updates to these terms.</p>
        </div>

        <div class="terms-section">
          <h3><i class='bx bx-shield-alt-2'></i> User Responsibilities</h3>
          <p>As an authorized user of this system, you are responsible for:</p>
          <ul>
            <li>Maintaining the confidentiality and security of your login credentials</li>
            <li>All activities and transactions performed under your account</li>
            <li>Ensuring the accuracy and completeness of information provided</li>
            <li>Complying with all company policies and applicable regulations</li>
            <li>Immediately reporting any suspicious activity or security breaches</li>
          </ul>
        </div>

        <div class="terms-section">
          <h3><i class='bx bx-lock-alt'></i> Data Privacy and Security</h3>
          <p>Microfinancial is committed to protecting your privacy and securing sensitive information:</p>
          <ul>
            <li>We collect only necessary personal and operational data required for system functionality</li>
            <li>Industry-standard encryption protocols protect data during transmission and storage</li>
            <li>Regular security audits and monitoring systems are in place</li>
            <li>Access to sensitive information is strictly role-based and monitored</li>
            <li>We comply with all applicable data protection and privacy regulations</li>
          </ul>
        </div>

        <div class="terms-section">
          <h3><i class='bx bx-cog'></i> System Usage Guidelines</h3>
          <p><strong>Permitted Activities:</strong></p>
          <ul>
            <li>Access authorized modules based on your assigned permissions</li>
            <li>Process legitimate logistics and financial transactions</li>
            <li>Generate and export reports for business purposes</li>
            <li>Collaborate with other authorized system users</li>
          </ul>
          <p><strong>Strictly Prohibited:</strong></p>
          <ul>
            <li>Unauthorized access to other users' data or restricted system areas</li>
            <li>Attempting to breach, test, or circumvent system security measures</li>
            <li>Sharing, lending, or transferring your login credentials to others</li>
            <li>Using the system for any illegal, fraudulent, or unauthorized purposes</li>
            <li>Introducing malicious code, viruses, or harmful components</li>
          </ul>
        </div>

        <div class="terms-section">
          <h3><i class='bx bx-time'></i> System Availability and Maintenance</h3>
          <p>The system is designed for high availability, but may experience temporary unavailability during:</p>
          <ul>
            <li>Planned maintenance windows (typically announced in advance)</li>
            <li>Emergency security updates and patches</li>
            <li>Infrastructure upgrades and improvements</li>
            <li>Unforeseen technical issues or force majeure events</li>
          </ul>
        </div>

        <div class="terms-section">
          <h3><i class='bx bx-revision'></i> Terms Modification</h3>
          <p>Microfinancial reserves the right to modify these Terms and Conditions at any time. Users will be notified of significant changes through system notifications or email. Continued use of the system after modifications constitutes acceptance of the updated terms.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-primary" id="agreeButton">
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

    function showErrorAlert(message) {
        const alert = document.getElementById('loginErrorAlert');
        const messageSpan = document.getElementById('loginErrorMessage');
        messageSpan.textContent = message;
        alert.classList.remove('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            hideErrorAlert();
        }, 5000);
    }

    function hideErrorAlert() {
        const alert = document.getElementById('loginErrorAlert');
        alert.classList.add('hidden');
    }

    // Terms and Conditions Modal Functions
    function openTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Event listeners for terms modal
    document.getElementById('termsLink').addEventListener('click', openTermsModal);
    document.getElementById('closeModal').addEventListener('click', closeTermsModal);
    document.getElementById('agreeButton').addEventListener('click', closeTermsModal);

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
        this.classList.remove('input-error');
        document.getElementById('emailError').style.display = 'none';
    });

    document.getElementById('password').addEventListener('input', function() {
        this.classList.remove('input-error');
        document.getElementById('passwordError').style.display = 'none';
    });

    async function handleLogin(event) {
        event.preventDefault();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        // Clear previous errors
        hideErrorAlert();
        document.getElementById('emailError').style.display = 'none';
        document.getElementById('passwordError').style.display = 'none';
        document.getElementById('email').classList.remove('input-error');
        document.getElementById('password').classList.remove('input-error');

        // Basic validation
        let hasError = false;
        if (!email) {
            document.getElementById('emailError').style.display = 'flex';
            document.getElementById('email').classList.add('input-error');
            hasError = true;
        }
        if (!password) {
            document.getElementById('passwordError').style.display = 'flex';
            document.getElementById('password').classList.add('input-error');
            hasError = true;
        }
        
        if (hasError) {
            return;
        }

        // Show loading alert
        Swal.fire({
            title: 'Verifying Credentials',
            text: 'Please wait while we secure your access...',
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
                // Store in both localStorage and cookies for redundancy
                localStorage.setItem('isAuthenticated', 'true');
                localStorage.setItem('user', JSON.stringify(data.user));
                
                // Set cookie that expires in 1 day
                document.cookie = `isAuthenticated=true; path=/; max-age=${24 * 60 * 60}`;
                document.cookie = `user=${encodeURIComponent(JSON.stringify(data.user))}; path=/; max-age=${24 * 60 * 60}`;

                Swal.fire({
                    icon: 'success',
                    title: 'Access Granted!',
                    text: 'Welcome back! Redirecting to Logistics System...',
                    timer: 1500,
                    showConfirmButton: false,
                    background: '#f0fdf4',
                    color: '#065f46'
                }).then(() => {
                    // Redirect to login splash first, then it will auto-redirect to dashboard
                    window.location.href = '/login-splash';
                });
            } else {
                throw new Error(data.message || 'Invalid email or password.');
            }
        } catch (error) {
            Swal.close(); // Close the loading alert
            showErrorAlert(error.message || 'Invalid email or password. Please check your credentials and try again.');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', handleLogin);

    // Check if user is already logged in - BUT ONLY REDIRECT IF ACTUALLY AUTHENTICATED
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we have valid authentication data, not just the existence of items
        const isAuthenticated = localStorage.getItem('isAuthenticated') === 'true';
        const hasUser = localStorage.getItem('user');
        
        // Also check cookies as backup
        const cookieAuth = document.cookie.split('; ').find(row => row.startsWith('isAuthenticated='));
        const cookieUser = document.cookie.split('; ').find(row => row.startsWith('user='));
        
        const isReallyAuthenticated = isAuthenticated && hasUser || 
                                   (cookieAuth && cookieAuth.split('=')[1] === 'true' && cookieUser);

        // Only redirect if we have valid authentication
        if (isReallyAuthenticated) {
            try {
                const user = hasUser ? JSON.parse(hasUser) : 
                            cookieUser ? JSON.parse(decodeURIComponent(cookieUser.split('=')[1])) : null;
                
                if (user && user.id) {
                    // If already authenticated, go directly to dashboard
                    window.location.href = '/dashboard';
                }
            } catch (e) {
                // If user data is invalid, stay on login page
                console.error('Invalid user data:', e);
                // Clear invalid data
                localStorage.removeItem('isAuthenticated');
                localStorage.removeItem('user');
            }
        }
    });
</script>
</body>
</html>