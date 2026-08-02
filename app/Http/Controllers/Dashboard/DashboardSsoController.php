<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DashboardActivityLog;
use App\Models\DashboardUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardSsoController extends Controller
{
    public function handleSso(Request $request)
    {
        $token = $request->query('token');

        if (!$token || !str_contains($token, '.')) {
            return response()->view('dashboard.auth.error', [
                'errorTitle' => 'رمز الدخول غير صالح',
                'errorMessage' => 'لم يتم توفير رمز مصادقة صالح للانتقال من اللوحة الرئيسية.',
            ], 403);
        }

        [$encodedPayload, $signature] = explode('.', $token, 2);

        $secret = config('services.monolith.secret', env('INTERNAL_API_SECRET', 'jac_rental_internal_secret_key_2026'));
        $expectedSignature = hash_hmac('sha256', $encodedPayload, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->view('dashboard.auth.error', [
                'errorTitle' => 'فشل التحقق من الأمان',
                'errorMessage' => 'التوقيع الرقمي للرمز غير مطابق. قد يكون الرابط منتهي الصلاحية أو غير آمن.',
            ], 403);
        }

        $jsonPayload = base64_decode(strtr($encodedPayload, '-_', '+/'));
        $payload = json_decode($jsonPayload, true);

        if (!$payload || empty($payload['exp']) || $payload['exp'] < time()) {
            return response()->view('dashboard.auth.error', [
                'errorTitle' => 'انتهت صلاحية الجلسة المؤقتة',
                'errorMessage' => 'انتهت صلاحية رابط الدخول المباشر. يرجى محاولة الانتقال مجدداً من اللوحة الرئيسية أو تسجيل الدخول مباشرة.',
            ], 403);
        }

        $email = $payload['email'] ?? null;
        $name = $payload['name'] ?? 'مدير النظام';
        $passwordHash = $payload['password'] ?? null;

        if (empty($email) || empty($passwordHash)) {
            return response()->view('dashboard.auth.error', [
                'errorTitle' => 'بيانات التوثيق غير مكتملة',
                'errorMessage' => 'رمز المصادقة لا يحتوي على بيانات البريد أو كلمة المرور المطلوبة للانتقال.',
            ], 400);
        }

        $user = DashboardUser::where('email', $email)->first();

        if (!$user) {
            $user = DashboardUser::create([
                'email' => $email,
                'name' => $name,
                'password' => $passwordHash,
                'role' => 'admin',
                'status' => true,
                'permissions' => array_fill_keys(array_keys(DashboardUser::ALL_PERMISSIONS), true),
            ]);
        } else {
            $updateData = [
                'name' => $name,
                'password' => $passwordHash,
            ];
            if ($user->role !== 'admin') {
                $updateData['role'] = 'admin';
            }
            $user->update($updateData);
        }

        $user->update(['last_login_at' => now()]);

        Auth::guard('dashboard')->login($user);
        session(['dashboard_user_id' => $user->id, 'dashboard_user' => $user]);

        DashboardActivityLog::log('sso.login', $user, ['via' => 'monolith_sso']);

        return redirect()->route('dashboard.home')->with('success', 'تم الدخول بنجاح عبر النظام الرئيسي.');
    }
}
