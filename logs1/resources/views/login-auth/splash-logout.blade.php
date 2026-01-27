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
</head>
<body>
    <main class="min-h-screen flex items-center justify-center bg-brand-primary p-6 relative overflow-hidden">
        <!-- Floating Shapes Background -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="shape absolute w-72 h-72 top-[5%] left-[-5%] bg-white/5 rounded-full animate-float"></div>
            <div class="shape shape-2 absolute w-96 h-96 bottom-[-20%] left-[15%] bg-white/5 rounded-full animate-float-delayed"></div>
            <div class="shape shape-3 absolute w-80 h-80 top-[-15%] right-[-10%] bg-white/5 rounded-full animate-float-reverse"></div>
            <div class="shape shape-4 absolute w-56 h-56 bottom-[5%] right-[10%] bg-white/5 rounded-full animate-float-fast"></div>
            <div class="shape shape-5 absolute w-48 h-48 top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2 bg-white/5 rounded-full animate-float-reverse-fast"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 flex flex-col items-center space-y-4">
                <img src="{{ asset('images/micrologo.png') }}"
                     alt="Microfinancial Logistics Logo"
                     class="w-20 h-20 sm:w-24 sm:h-24 md:w-32 md:h-32 lg:w-36 lg:h-36 object-contain" />

                <div role="status" aria-live="polite" class="flex items-center space-x-3">
                    <i class="bx bx-loader-alt bx-spin text-brand-primary text-4xl" aria-hidden="true"></i>
                    <span class="text-brand-text-primary font-medium text-lg">Logging out…</span>
                </div>

                <p class="text-sm text-brand-text-secondary text-center">You have been signed out. Redirecting to the login page…</p>
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