<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-600 to-green-400 flex justify-center items-center min-h-screen m-0">
  <div class="text-center text-white">
    <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Logo" class="mx-auto h-32 mb-4">
    <h2 class="text-3xl font-bold mb-2">Microfinancial</h2>
    <p class="text-lg">Welcome back, <span id="userName"></span>!</p>
    <p class="text-sm mt-2">You are now redirecting to the dashboard...</p>
  </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get user data from cookie or localStorage
        let user = null;
        
        // Try to get from cookie first
        const userCookie = document.cookie.split('; ').find(row => row.startsWith('user='));
        if (userCookie) {
            try {
                user = JSON.parse(decodeURIComponent(userCookie.split('=')[1]));
            } catch (e) {
                console.error('Error parsing user cookie:', e);
            }
        }
        
        // Fallback to localStorage
        if (!user) {
            user = JSON.parse(localStorage.getItem('user') || '{}');
        }
        
        const userName = user.firstname || 'User';
        document.getElementById('userName').textContent = userName;

        setTimeout(() => {
            window.location.href = '/dashboard';
        }, 2000);
    });
</script>
</body>
</html>