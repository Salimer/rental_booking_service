<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DashboardActivityLog;
use App\Models\DashboardUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardAuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::guard('dashboard')->check()) {
            return redirect()->route('dashboard.home');
        }

        return view('dashboard.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = DashboardUser::where('email', strtolower(trim($request->email)))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'بيانات الاعتماد غير صحيحة. يرجى التأكد من البريد وكلمة المرور.']);
        }

        if (!$user->status) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'هذا الحساب معطل حالياً. يرجى التواصل مع إدارة النظام.']);
        }

        $user->update(['last_login_at' => now()]);

        Auth::guard('dashboard')->login($user, $request->boolean('remember'));
        session(['dashboard_user_id' => $user->id, 'dashboard_user' => $user]);

        DashboardActivityLog::log('user.login', $user, ['via' => 'direct_form']);

        return redirect()->intended(route('dashboard.home'))
            ->with('success', 'أهلاً بك، تم تسجيل الدخول بنجاح.');
    }

    public function logout(Request $request)
    {
        $user = session('dashboard_user');
        if ($user) {
            DashboardActivityLog::log('user.logout', $user);
        }

        Auth::guard('dashboard')->logout();
        $request->session()->forget(['dashboard_user_id', 'dashboard_user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
