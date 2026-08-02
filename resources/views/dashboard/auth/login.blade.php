<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام التأجير JAC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard-colors.css') }}">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: var(--gradient-brand-header);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: var(--color-card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow-auth);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
        .brand-logo {
            background: var(--color-brand-gold);
            color: var(--color-text-dark);
            width: 60px;
            height: 60px;
            border-radius: 16px;
            font-size: 28px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: var(--shadow-gold);
        }
        .btn-submit {
            background: var(--color-brand-gold);
            border: none;
            color: var(--color-text-dark);
            font-weight: 700;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-submit:hover {
            background: var(--color-brand-gold-hover);
            color: var(--color-text-dark);
        }
    </style>
</head>
<body>

    <div class="login-card text-center">
        <div class="brand-logo">JAC</div>
        <h4 class="fw-bold mb-1">تسجيل الدخول</h4>
        <p class="text-muted fs-7 mb-4">لوحة تحكم نظام التأجير وإدارة الوحدات</p>

        @if($errors->any())
            <div class="alert alert-danger text-start py-2 fs-7 mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success text-start py-2 fs-7 mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('dashboard.login') }}" method="POST" class="text-start">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold fs-7 mb-1">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control form-control-lg rounded-3 fs-6" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold fs-7 mb-1">كلمة المرور</label>
                <input type="password" name="password" class="form-control form-control-lg rounded-3 fs-6" required placeholder="••••••••">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 fs-7">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">تذكرني على هذا الجهاز</label>
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100 fs-6">دخول اللوحة</button>
        </form>
    </div>

</body>
</html>
