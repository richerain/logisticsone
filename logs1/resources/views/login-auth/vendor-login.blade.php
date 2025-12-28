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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <main class="flex items-center justify-center min-h-screen bg-green-700 p-6">
        <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-900 p-8 flex items-center justify-center">
                <div class="text-center">
                    <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Vendors Logo" class="w-20 h-20 sm:w-24 sm:h-24 md:w-40 md:h-40 lg:w-56 lg:h-56 mx-auto mb-4 object-contain" />
                    <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-white">Microfinancial Vendors</h3>
                    <p class="text-xs sm:text-sm text-green-100 mt-2">Secure access to Vendors Portal.</p>
                </div>
            </div>
            <div class="md:w-1/2 p-8">
                <h2 class="text-3xl font-bold text-gray-700 text-center">Vendor Login</h2>
                <p class="text-center text-gray-600 mb-6">Enter your vendor credentials to access the portal.</p>

                <form id="loginForm" class="space-y-4" novalidate>
                    @csrf
                    <div id="login-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative hidden" role="alert">
                        <div class="flex items-center">
                            <i class='bx bxs-error-circle text-red-600 mr-2'></i>
                            <span id="login-error-message" class="text-red-600 text-sm">Login failed. Please check your credentials and try again.</span>
                        </div>
                    </div>

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

                    <label class="block relative">
                        <span class="text-gray-700">Password</span>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bx bxs-lock text-gray-400 text-lg" aria-hidden="true"></i>
                            </span>
                            <input id="password" name="password" type="password" placeholder="Password" class="input input-bordered w-full pl-10 pr-12 mt-0" aria-label="Password" required/>
                            <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500" aria-pressed="false" aria-label="Show password">
                                <i class="bx bx-show-alt text-lg"></i>
                            </button>
                        </div>
                        <span id="password-error" class="text-red-600 text-sm hidden"></span>
                    </label>

                    <div class="flex items-center justify-between">
                        <label for="agree" class="flex items-center space-x-2 cursor-pointer">
                            <input id="agree" name="agree" type="checkbox" class="checkbox h-4 w-4 md:h-5 md:w-5 shrink-0 align-middle" aria-checked="false" required />
                            <span class="text-sm text-gray-600 leading-tight">
                                I have read and agree to the
                                <a href="#" id="terms-link" class="text-purple-600 hover:underline">Terms &amp; Conditions</a>
                            </span>
                        </label>
                    </div>
                    <span id="agree-error" class="text-red-600 text-sm hidden"></span>

                    <button type="submit" id="loginBtn" class="btn btn-primary w-full">
                        <span id="loginText">Login</span>
                        <span id="loginSpinner" class="loading loading-spinner loading-sm hidden"></span>
                    </button>
                    <div class="mt-2 ml-4 text-left">
                        <a href="/login" class="text-purple-600 hover:underline text-sm">Login Employee Account?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

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
    handleLogin();
});

document.addEventListener('DOMContentLoaded', function() {
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
    fetch('/api/vendor/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
        credentials: 'include',
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => { if (!response.ok) { return response.json().then(errorData => { throw new Error(errorData.message || `Login failed with status: ${response.status}`); }).catch(() => { throw new Error(`Login failed with status: ${response.status}`); }); } return response.json(); })
    .then(data => { if (data.success) { if (data.requires_otp) { window.location.href = `/otp-verification?portal=vendor&email=${encodeURIComponent(data.email)}`; } else { window.location.href = '/vendor/splash-login'; } } else { throw new Error(data.message || 'Login failed. Please check your credentials and try again.'); } })
    .catch(error => { const loginError = document.getElementById('login-error'); const loginErrorMessage = document.getElementById('login-error-message'); loginErrorMessage.textContent = error.message; loginError.classList.remove('hidden'); loginError.scrollIntoView({ behavior: 'smooth', block: 'center' }); setTimeout(() => { loginError.classList.add('hidden'); }, 8000); })
    .finally(() => { loginBtn.disabled = false; loginText.textContent = 'Login'; loginSpinner.classList.add('hidden'); });
}

function showError(id, message) { const el = document.getElementById(id); el.textContent = message; el.classList.remove('hidden'); }

function togglePassword(btn){ var input = document.getElementById('password'); var icon = btn.querySelector('i'); if (!input) return; if (input.type === 'password') { input.type = 'text'; icon.classList.remove('bx-show-alt'); icon.classList.add('bx-hide'); btn.setAttribute('aria-pressed', 'true'); btn.setAttribute('aria-label', 'Hide password'); } else { input.type = 'password'; icon.classList.remove('bx-hide'); icon.classList.add('bx-show-alt'); btn.setAttribute('aria-pressed', 'false'); btn.setAttribute('aria-label', 'Show password'); } }

console.log('Vendor login form loaded successfully');
</script>
</body>
</html>