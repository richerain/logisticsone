<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout</title>
  <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-600 to-green-400 flex justify-center items-center min-h-screen m-0">
  <div class="text-center text-white">
    <img src="{{ asset('images/micrologo.png') }}" alt="Microfinancial Logo" class="mx-auto h-32 mb-4">
    <h2 class="text-3xl font-bold mb-2">Microfinance</h2>
    <p class="text-lg">Thank you for your service. Goodbye!</p>
    <p class="text-sm mt-2">You are now redirecting to the login...</p>
  </div>

  <script>
    setTimeout(() => {
      window.location.href = '/login';
    }, 2000);
  </script>
</body>
</html>