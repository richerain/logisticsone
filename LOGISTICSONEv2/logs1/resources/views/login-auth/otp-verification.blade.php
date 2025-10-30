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
                    <strong class="text-gray-800">{{ $email ?? 'mail@site.com' }}</strong>.
                    Didn't receive it? You can resend after the timer expires.
                </p>

                <!-- otp form start -->
                <form id="otp-form" class="space-y-4" onsubmit="return false;" novalidate>
                    <!-- hidden email (if you want to send it back to server) -->
                    <input type="hidden" name="email" value="{{ $email ?? 'mail@site.com' }}" />

                    <!-- OTP inputs (single row on all screen sizes) -->
                    <div class="grid grid-cols-6 gap-2 justify-items-center">
                        <input name="otp[]" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 1" />
                        <input name="otp[]" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 2" />
                        <input name="otp[]" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 3" />
                        <input name="otp[]" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 4" />
                        <input name="otp[]" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 5" />
                        <input name="otp[]" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code" spellcheck="false" class="otp input input-bordered w-10 h-10 sm:w-12 sm:h-12 text-center text-lg sm:text-xl" aria-label="Digit 6" />
                    </div>

                    <!-- helper text / error -->
                    <p id="otp-error" class="text-center text-red-600 text-sm hidden">Please enter the full 6-digit code.</p>

                    <!-- resend / timer row -->
                    <div class="flex items-center justify-start">
                        <div class="text-sm text-gray-600">
                            <button id="resend-btn" type="button" class="text-blue-600 hover:underline disabled:opacity-50" disabled>Resend OTP</button>
                            <span id="timer" class="ml-2 text-gray-500">(00:60)</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <button id="verify-btn" type="button" class="btn btn-primary w-full">Verify OTP</button>
                        <button id="cancel-btn" type="button" class="btn btn-ghost w-full">Cancel</button>
                    </div>
                </form>
                <!-- otp form end -->

            </div>
            <!-- otp verification form end -->
            </div>
            <!-- login form panel end -->
        </div>
    </main>
</body>
</html>