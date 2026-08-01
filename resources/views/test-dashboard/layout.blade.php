<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('dashboard.title'))</title>
    <!-- Google Fonts Arabic (Cairo & Tajawal) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            900: '#0b0f19',
                            800: '#111827',
                            700: '#1f2937',
                            600: '#374151',
                        },
                        brand: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #0b0f19;
            color: #f3f4f6;
        }
        .glass-panel {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-dark-900 text-gray-100 font-sans antialiased">

    <!-- Top Navigation Header -->
    <header class="glass-panel sticky top-0 z-50 border-b border-gray-800 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4 space-x-reverse">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center font-bold text-xl shadow-lg text-white">
                🏠
            </div>
            <div>
                <h1 class="text-lg font-extrabold bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 bg-clip-text text-transparent">
                    {{ __('dashboard.title') }}
                </h1>
                <p class="text-xs text-gray-400">
                    {{ __('dashboard.subtitle') }}
                </p>
            </div>
        </div>

        <!-- Connection & Environment Metadata Badges -->
        <div class="hidden md:flex items-center space-x-3 space-x-reverse text-xs">
            <span class="px-3 py-1.5 rounded-lg glass-card flex items-center gap-2 border border-emerald-500/20 text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ __('dashboard.db_info') }} <strong class="text-white">{{ config('database.connections.mysql.database') }}</strong></span>
            </span>
            <span class="px-3 py-1.5 rounded-lg glass-card text-purple-300 border border-purple-500/20">
                {{ __('dashboard.env_info') }} <strong class="text-white">{{ config('app.env') }}</strong>
            </span>
            <span class="px-3 py-1.5 rounded-lg glass-card text-amber-300 border border-amber-500/20">
                المرجع: <strong class="text-white">AR (RTL)</strong>
            </span>
        </div>
    </header>

    <!-- Main Content Area with Sidebar -->
    <div class="flex-1 flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 glass-panel border-l border-gray-800 p-4 shrink-0 flex flex-col justify-between">
            <nav class="space-y-1.5">
                <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    قائمة التنقل والمجالات
                </div>
                
                <a href="{{ route('test-dashboard.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('test-dashboard.index') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}">
                    <span class="text-lg">📊</span>
                    <span>{{ __('dashboard.nav.overview') }}</span>
                </a>

                <a href="{{ route('test-dashboard.properties') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('test-dashboard.properties') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}">
                    <span class="text-lg">🏢</span>
                    <span>{{ __('dashboard.nav.properties') }}</span>
                </a>

                <a href="{{ route('test-dashboard.bookings') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('test-dashboard.bookings') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}">
                    <span class="text-lg">📅</span>
                    <span>{{ __('dashboard.nav.bookings') }}</span>
                </a>

                <a href="{{ route('test-dashboard.coupons') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('test-dashboard.coupons') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}">
                    <span class="text-lg">🎟️</span>
                    <span>{{ __('dashboard.nav.coupons') }}</span>
                </a>

                <a href="{{ route('test-dashboard.orgs') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('test-dashboard.orgs') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}">
                    <span class="text-lg">🏛️</span>
                    <span>{{ __('dashboard.nav.orgs') }}</span>
                </a>

                <div class="pt-4 px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    أدوات التطوير والتجربة
                </div>

                <a href="{{ route('test-dashboard.api-tester') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('test-dashboard.api-tester') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-emerald-400 hover:bg-emerald-950/40 hover:text-emerald-300 border border-emerald-500/20' }}">
                    <span class="text-lg">⚡</span>
                    <span>{{ __('dashboard.nav.api_tester') }}</span>
                </a>
            </nav>

            <div class="pt-6 border-t border-gray-800/80 text-xs text-gray-500 text-center">
                بيئة فحص مستقلة للمايكروسيرفس <br> Microservice Standalone Test
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Scripts Section -->
    @stack('scripts')
</body>
</html>
