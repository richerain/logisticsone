<?php

namespace App\Http\Middleware;

use App\Models\EmployeeAccount;
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
                return response()->json(['success' => false, 'message' => 'Unauthorized: No secret key'], 401);
            }

            $payload = JWT::decode($token, new Key($secret, 'HS256'));
            if (! isset($payload->sub) || ! isset($payload->exp) || $payload->exp < time()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $user = EmployeeAccount::find($payload->sub);
            if (! $user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $request->attributes->set('jwt_user', $user);
            Auth::shouldUse('sws');
            Auth::setUser($user);

            return $next($request);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }
}
