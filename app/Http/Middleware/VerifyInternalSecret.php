<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyInternalSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = env('INTERNAL_API_SECRET', 'jac_rental_internal_secret_key_2026');
        $providedSecret = $request->header('X-Internal-Secret');

        if (!$providedSecret || !hash_equals($secret, $providedSecret)) {
            return response()->json(['message' => 'Unauthorized internal call'], 401);
        }

        return $next($request);
    }
}
