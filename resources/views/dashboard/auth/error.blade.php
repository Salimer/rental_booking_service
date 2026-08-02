<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $errorTitle ?? 'تنبيه الأمان' }} - JAC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard-colors.css') }}">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: var(--color-bg-auth);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: var(--color-card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow-error);
            width: 100%;
            max-width: 480px;
            padding: 40px;
            text-align: center;
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--color-danger-bg);
            color: var(--color-danger-text);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="icon-circle">!</div>
        <h4 class="fw-bold mb-2">{{ $errorTitle ?? 'حدث خطأ في عملية الانتقال' }}</h4>
        <p class="text-muted fs-6 mb-4">{{ $errorMessage ?? 'لم نتمكن من التحقق من توقيع الجلسة الانتقالية.' }}</p>

        <div class="d-grid gap-2">
            <a href="{{ route('dashboard.login') }}" class="btn btn-brand-primary btn-lg rounded-3 fs-6">
                تسجيل الدخول المباشر
            </a>
        </div>
    </div>

</body>
</html>
