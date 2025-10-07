<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/combine/npm/daisyui@5/base/rootscrollgutter.css,npm/daisyui@5/base/scrollbar.css,npm/daisyui@5/base/rootcolor.css,npm/daisyui@5/base/rootscrolllock.css,npm/daisyui@5/base/reset.css,npm/daisyui@5/base/svg.css,npm/daisyui@5/base/properties.css,npm/daisyui@5/components/input.css,npm/daisyui@5/components/timeline.css,npm/daisyui@5/components/swap.css,npm/daisyui@5/components/divider.css,npm/daisyui@5/components/rating.css,npm/daisyui@5/components/steps.css,npm/daisyui@5/components/hovergallery.css,npm/daisyui@5/components/loading.css,npm/daisyui@5/components/diff.css,npm/daisyui@5/components/filter.css,npm/daisyui@5/components/badge.css,npm/daisyui@5/components/table.css,npm/daisyui@5/components/radio.css,npm/daisyui@5/components/indicator.css,npm/daisyui@5/components/collapse.css,npm/daisyui@5/components/link.css,npm/daisyui@5/components/dropdown.css,npm/daisyui@5/components/calendar.css,npm/daisyui@5/components/dock.css,npm/daisyui@5/components/mask.css,npm/daisyui@5/components/mockup.css,npm/daisyui@5/components/chat.css,npm/daisyui@5/components/navbar.css,npm/daisyui@5/components/fab.css,npm/daisyui@5/components/list.css,npm/daisyui@5/components/carousel.css,npm/daisyui@5/components/drawer.css,npm/daisyui@5/components/kbd.css,npm/daisyui@5/components/avatar.css,npm/daisyui@5/components/alert.css,npm/daisyui@5/components/countdown.css,npm/daisyui@5/components/hero.css,npm/daisyui@5/components/menu.css,npm/daisyui@5/components/status.css,npm/daisyui@5/components/toggle.css,npm/daisyui@5/components/range.css,npm/daisyui@5/components/validator.css,npm/daisyui@5/components/fieldset.css,npm/daisyui@5/components/label.css,npm/daisyui@5/components/skeleton.css,npm/daisyui@5/components/stack.css,npm/daisyui@5/components/footer.css,npm/daisyui@5/components/breadcrumbs.css,npm/daisyui@5/components/toast.css,npm/daisyui@5/components/stat.css,npm/daisyui@5/components/radialprogress.css,npm/daisyui@5/components/tab.css,npm/daisyui@5/components/checkbox.css,npm/daisyui@5/components/select.css,npm/daisyui@5/components/fileinput.css,npm/daisyui@5/components/card.css,npm/daisyui@5/components/textarea.css,npm/daisyui@5/components/button.css,npm/daisyui@5/components/modal.css,npm/daisyui@5/components/tooltip.css,npm/daisyui@5/components/progress.css,npm/daisyui@5/utilities/typography.css,npm/daisyui@5/utilities/radius.css,npm/daisyui@5/utilities/glass.css,npm/daisyui@5/utilities/join.css,npm/daisyui@5/colors/properties-extended.css,npm/daisyui@5/colors/responsive.css,npm/daisyui@5/colors/states-extended.css,npm/daisyui@5/colors/states.css,npm/daisyui@5/colors/properties.css,npm/daisyui@5/colors/responsive-extended.css,npm/daisyui@5/theme/light.css" rel="stylesheet" type="text/css" />
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #2f855a, #48bb78, #38a169);
      font-family: Arial, sans-serif;
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
      font-family: Arial, sans-serif;
      padding: 1rem;
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
      margin-top: 2rem;
    }
    .company-logo img {
      width: 300px;
      height: auto;
      opacity: 1;
    }
    .company-text h1 {
      font-size: 3rem;
    }
    .company-text p {
      font-size: 1.5rem;
    }
    .panel {
      width: 100%;
      max-width: calc(40vw - 4rem);
      padding: 1.5rem;
      display: none;
      box-sizing: border-box;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      margin: 0 auto;
    }
    .panel.active {
      display: block;
    }
    .btn-green {
      background-color: #10b981;
      color: #f3f4f6;
      border: none;
      text-transform: none;
      font-family: Arial, sans-serif;
    }
    .btn-green:hover {
      background-color: #059669;
    }
    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #4b5563;
      z-index: 10;
    }
    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #4b5563;
      cursor: pointer;
      z-index: 10;
    }
    .input:focus ~ .input-icon,
    .input:focus ~ .password-toggle {
      color: #059669;
    }
    .error-message {
      color: #ef4444;
      font-size: 0.875rem;
      margin-top: 0.25rem;
      display: none;
      text-transform: none;
      font-family: Arial, sans-serif;
    }
    .input-error {
      border-color: #ef4444 !important;
    }
    .custom-alert {
      border-left: 4px solid #ef4444;
      background-color: #fef2f2;
      padding: 1rem;
      border-radius: 0.375rem;
      display: none;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }
    .custom-alert i {
      color: #ef4444;
      font-size: 1.25rem;
    }
    .custom-alert span {
      color: #7f1d1d;
      font-size: 0.875rem;
      text-transform: none;
      font-family: Arial, sans-serif;
    }
    .forgot-password {
      text-align: right;
      text-transform: none;
      font-family: Arial, sans-serif;
    }
    .card-title, .label-text, .btn {
      text-transform: none;
    }
    .panel-title {
      width: 100%;
    }
    .login-subtitle {
      text-align: center;
      color: #6b7280;
      font-size: 1rem;
      margin-top: -0.5rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="section-1">
      <div class="company-content">
        <div class="company-logo">
          <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Logo">
        </div>
        <div class="company-text uppercase relative z-index-2 text-gray-800">
          <h1 class="font-bold text-gold-500">Microfinance 1</h1>
          <p class="mt-0.1 text-gold-600">Logistics I Department</p>
        </div>
      </div>
    </div>

    <div class="section-2">
      <div id="loginPanel" class="panel active border-b-4 border-t-4 border-green-600">
        <div class="card w-full bg-base-100">
          <div class="card-body"> 
            <div class="text-center text-gray-600 font-bold text-xl uppercase">Welcome to Logistics I</div>
            <form id="loginForm" class="space-y-6">
              <div id="invalidAlert" class="custom-alert">
                <i class='bx bxs-error-circle'></i>
                <span id="invalidAlertMessage">Invalid email or password</span>
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text text-gray-600 font-medium">Email</span>
                </label>
                <div class="relative">
                  <input type="email" id="email" name="email" placeholder="Enter your email" class="input input-bordered input-lg w-full bg-gray-50 text-gray-800 focus:border-green-500 transition-all pl-10">
                  <i class='bx bxs-envelope input-icon text-lg'></i>
                </div>
                <div class="error-message" id="emailError">The email is required</div>
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text text-gray-600 font-medium">Password</span>
                </label>
                <div class="relative">
                  <input type="password" id="password" name="password" placeholder="Enter your password" class="input input-bordered input-lg w-full bg-gray-50 text-gray-800 focus:border-green-500 transition-all pl-10 pr-10">
                  <i class='bx bxs-lock-alt input-icon text-lg'></i>
                  <i class='bx bx-show password-toggle text-lg' id="togglePassword"></i>
                </div>
                <div class="error-message" id="passwordError">The password is required</div>
              </div>
              <div class="card-actions">
                <button type="submit" class="btn btn-green btn-lg w-full mt-4">Login</button>
              </div>
            </form>
          </div>
        </div>
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

    async function handleLogin(event) {
        event.preventDefault();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        // Basic validation
        if (!email || !password) {
            document.getElementById('invalidAlert').style.display = 'flex';
            return;
        }

        document.getElementById('invalidAlert').style.display = 'none';

        // Show loading alert
        Swal.fire({
            title: 'Verifying Login',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('/api/auth/login', {
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
                    title: 'Successfully Login!',
                    text: 'Redirecting to...',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '/login-splash';
                });
            } else {
                throw new Error(data.message || 'Login failed');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: error.message || 'Invalid email or password'
            });
        }
    }

    document.getElementById('loginForm').addEventListener('submit', handleLogin);

    // Check if user is already logged in - BUT ONLY REDIRECT IF ACTUALLY AUTHENTICATED
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we have valid authentication data, not just the existence of items
        const isAuthenticated = localStorage.getItem('isAuthenticated') === 'true';
        const hasUser = localStorage.getItem('user');
        
        // Only redirect if we have both authentication flag AND user data
        if (isAuthenticated && hasUser) {
            try {
                const user = JSON.parse(hasUser);
                if (user && user.id) {
                    window.location.href = '/dashboard';
                }
            } catch (e) {
                // If user data is invalid, stay on login page
                console.error('Invalid user data:', e);
            }
        }
    });
</script>
</body>
</html>