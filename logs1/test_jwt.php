<?php
try {
    $user = \App\Models\EmployeeAccount::first();
    if (!$user) { echo "No user found\n"; exit; }
    echo "User: " . $user->id . "\n";

    $authService = app(\App\Services\AuthService::class);
    $token = $authService->generateTokenForUser($user);
    
    if (!$token) {
        echo "Token generation failed (returned null)\n";
        // Check logs for why?
        // Let's verify secret presence directly
        $secret = env('JWT_SECRET');
        echo "env(JWT_SECRET): " . ($secret ? 'SET' : 'EMPTY') . "\n";
        exit;
    }
    
    echo "Token: " . substr($token, 0, 20) . "...\n";

    // Decode logic from JwtAuth
    $secret = env('JWT_SECRET');
    if (empty($secret)) {
        $secret = config('app.key');
        if (is_string($secret) && str_starts_with($secret, 'base64:')) {
            $secret = base64_decode(substr($secret, 7));
        }
    }
    echo "Secret used for decode: " . (empty($secret) ? "EMPTY" : "SET") . "\n";

    $payload = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secret, 'HS256'));
    echo "Decode Success!\n";
    echo "Sub: " . $payload->sub . "\n";
    
    $foundUser = \App\Models\EmployeeAccount::find($payload->sub);
    echo "Found User: " . ($foundUser ? "YES" : "NO") . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
