<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $errorTitle ?? 'تنبيه الأمان' }} - JAC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #F4F6F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 480px;
            padding: 40px;
            text-align: center;
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #FFEBEB;
            color: #DC3545;
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
            <a href="{{ route('dashboard.login') }}" class="btn btn-primary btn-lg rounded-3 fs-6" style="background-color: #005555; border-color: #005555;">
                تسجيل الدخول المباشر
            </a>
        </div>
    </div>

</body>
</html>
