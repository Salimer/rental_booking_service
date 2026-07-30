<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateViaMonolith
{
    /**
     * Handle an incoming request by introspecting the Bearer token via monolith.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'message' => 'Unauthenticated. Bearer token required.',
            ], 401);
        }

        $tokenHash = hash('sha256', $token);
        $cacheKey = "auth_token_{$tokenHash}";

        // 1. Check cache for token validation payload
        $userData = Cache::get($cacheKey);

        if (! $userData) {
            $monolithUrl = rtrim(config('services.monolith.url', env('MONOLITH_URL', 'http://localhost:8000')), '/');

            try {
                $response = Http::withToken($token)
                    ->acceptJson()
                    ->get("{$monolithUrl}/api/v1/auth/me");

                if (! $response->successful()) {
                    return response()->json([
                        'message' => 'Invalid or expired token.',
                        'errors' => [['code' => 'unauthenticated', 'message' => 'Token validation failed']],
                    ], 401);
                }

                $userData = $response->json();

                // Calculate TTL based on token_expires_at
                $ttl = 86400; // Default 24 hours fallback
                if (! empty($userData['token_expires_at'])) {
                    $expiresAt = Carbon::parse($userData['token_expires_at']);
                    $diffInSeconds = now()->diffInSeconds($expiresAt, false);
                    if ($diffInSeconds > 0) {
                        $ttl = $diffInSeconds;
                    }
                }

                Cache::put($cacheKey, $userData, $ttl);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to connect to authentication server.',
                    'error' => $e->getMessage(),
                ], 503);
            }
        }

        // 2. Provision or update local user record (Lazy First Touch)
        $userId = $userData['id'];
        $user = User::find($userId);

        $userPayload = [
            'id' => $userId,
            'f_name' => $userData['f_name'] ?? null,
            'l_name' => $userData['l_name'] ?? null,
            'name' => $userData['name'] ?? null,
            'phone' => $userData['phone'] ?? null,
            'email' => $userData['email'] ?? null,
            'current_language_key' => $userData['current_language_key'] ?? 'ar',
        ];

        if (! $user) {
            $user = User::create($userPayload);
        } else {
            $user->update($userPayload);
        }

        // 3. Attach authenticated user to request
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
