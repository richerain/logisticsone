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
    <p class="text-lg">Welcome back, <span id="userName">{{ $user['firstname'] ?? 'User' }}</span>!</p>
    <p class="text-sm mt-2">You are now redirecting to the dashboard...</p>
  </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Login splash page loaded - initializing session');
        
        // Get user data from PHP variable or cookie
        const userName = document.getElementById('userName').textContent;
        console.log('Welcome message for:', userName);

        // Update last activity timestamp to ensure fresh session
        const currentTime = Date.now();
        
        // Update localStorage
        localStorage.setItem('lastActivity', currentTime.toString());
        localStorage.setItem('isAuthenticated', 'true');
        
        // Update cookies
        document.cookie = `lastActivity=${currentTime}; path=/; max-age=${30 * 24 * 60 * 60}`;
        document.cookie = `isAuthenticated=true; path=/; max-age=${30 * 24 * 60 * 60}`;
        
        console.log('Session refreshed with timestamp:', currentTime);

        // Initialize session with backend
        fetch('/api/session/initialize', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json())
          .then(data => {
              console.log('Session initialized:', data);
          })
          .catch(error => {
              console.error('Session initialization error:', error);
          });

        // Redirect to Dashboard after 2 seconds
        setTimeout(() => {
            console.log('Redirecting to Dashboard...');
            window.location.href = '/dashboard';
        }, 2000);
    });
</script>
</body>
</html>