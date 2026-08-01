<?php

namespace App\Http\Middleware;

use App\Models\DashboardUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyDashboardSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('dashboard')->check()) {
            $user = Auth::guard('dashboard')->user();
            session(['dashboard_user' => $user]);
            return $next($request);
        }

        if (session()->has('dashboard_user_id')) {
            $user = DashboardUser::find(session('dashboard_user_id'));
            if ($user && $user->status) {
                Auth::guard('dashboard')->login($user);
                session(['dashboard_user' => $user]);
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('dashboard.login')->with('error', 'يرجى تسجيل الدخول للوصول إلى لوحة تحكم التأجير.');
    }
}
