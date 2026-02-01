<?php

namespace App\Http\Middleware;

use App\Models\EmployeeAccount;
use App\Models\VendorAccount;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->headers->get('Authorization');
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            \Illuminate\Support\Facades\Log::warning('JwtAuth: Missing or invalid Authorization header');
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $token = substr($authHeader, 7);
        try {
            // Prioritize JWT_SECRET from env
            $secret = env('JWT_SECRET');

            // Fallback to app.key if JWT_SECRET is missing
            if (empty($secret)) {
                $secret = config('app.key');
                if (is_string($secret) && str_starts_with($secret, 'base64:')) {
                    $secret = base64_decode(substr($secret, 7));
                }
            }

            if (empty($secret)) {
                \Illuminate\Support\Facades\Log::error('JwtAuth: No secret key found');
                return response()->json(['success' => false, 'message' => 'Unauthorized: No secret key'], 401);
            }

            $payload = JWT::decode($token, new Key($secret, 'HS256'));
            if (! isset($payload->sub) || ! isset($payload->exp) || $payload->exp < time()) {
                \Illuminate\Support\Facades\Log::warning('JwtAuth: Invalid payload or expired token', ['payload' => (array)$payload, 'time' => time()]);
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $user = null;
            $guard = 'sws';

            if (isset($payload->type) && $payload->type === 'vendor') {
                $user = VendorAccount::find($payload->sub);
                $guard = 'vendor';
            } else {
                $user = EmployeeAccount::find($payload->sub);
            }

            if (! $user) {
                \Illuminate\Support\Facades\Log::warning('JwtAuth: User not found', ['sub' => $payload->sub, 'type' => $payload->type ?? 'unknown']);
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $request->attributes->set('jwt_user', $user);
            Auth::shouldUse($guard);
            Auth::setUser($user);

            return $next($request);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('JwtAuth: Token decode failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }
}
