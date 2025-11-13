<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}" />
    <title>Logout Splash</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body>
    <main class="min-h-screen flex items-center justify-center bg-green-700 p-6">
        <div class="w-full max-w-md">
            <div class="bg-gray-800/95 rounded-lg shadow-xl p-8 flex flex-col items-center space-y-4">
                <img src="{{ asset('images/micrologo.png') }}"
                     alt="Microfinancial Logistics Logo"
                     class="w-20 h-20 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-36 lg:h-36 object-contain" />

                <div role="status" aria-live="polite" class="flex items-center space-x-3">
                    <i class="bx bx-loader-alt bx-spin text-green-500 text-4xl" aria-hidden="true"></i>
                    <span class="text-gray-100 font-medium text-lg">Logging out…</span>
                </div>

                <p class="text-sm text-gray-100 text-center">You have been signed out. Redirecting to the login page…</p>
            </div>
        </div>
    </main>

    <script>
        (async function() {
            try {
                await fetch('/api/v1/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });
            } catch (e) {
                // ignore errors and proceed
            } finally {
                try { localStorage.removeItem('jwt'); localStorage.removeItem('jwt_exp'); } catch (e) {}
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1000);
            }
        })();
    </script>
</body>
</html>