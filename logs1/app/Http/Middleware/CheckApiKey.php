<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY') ?? $request->query('key');

        if (!$key) {
            return response()->json(['message' => 'API Key required'], 401);
        }

        // Allow specific configured key(s) without DB lookup
        $envKey = env('PSM_EXTERNAL_API_KEY');
        $defaultKey = '63cfb7730dcc34299fa38cb1a620f701';
        if ($key === $envKey || $key === $defaultKey) {
            return $next($request);
        }

        $exists = DB::connection('main')->table('api_keys')
            ->where('key', $key)
            ->where('status', 'active')
            ->exists();

        if (!$exists) {
            return response()->json(['message' => 'Invalid API Key'], 403);
        }

        return $next($request);
    }
}
