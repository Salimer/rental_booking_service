<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة تحكم نظام التأجير - JAC')</title>

    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- Tabler Icons & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.net/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Central Theme Colors -->
    <link rel="stylesheet" href="{{ asset('css/dashboard-colors.css') }}">

    <style>
        :root {
            --primary-color: var(--color-brand-gold);
            --primary-hover: var(--color-brand-gold-hover);
            --primary-dark: var(--color-brand-navy);
            --bg-light: var(--color-bg-app);
            --sidebar-bg: var(--color-sidebar-bg);
            --sidebar-text: var(--color-sidebar-text);
            --sidebar-active-bg: var(--color-sidebar-active-bg);
            --sidebar-active-text: var(--color-sidebar-active-text);
            --card-border: var(--color-card-border);
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--color-bg-app);
            color: var(--color-text-main);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            z-index: 1040;
            box-shadow: var(--shadow-sidebar);
        }

        #sidebar .brand-header {
            padding: 20px;
            background: var(--color-sidebar-header-bg);
            border-bottom: 1px solid var(--color-sidebar-border);
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link:hover {
            color: var(--color-text-white);
            background: var(--color-sidebar-hover-bg);
        }

        #sidebar .nav-link.active {
            color: var(--sidebar-active-text);
            background: var(--sidebar-active-bg);
            font-weight: 700;
            box-shadow: var(--shadow-gold);
        }

        #sidebar .nav-link i {
            font-size: 20px;
        }

        .nav-category {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--color-sidebar-category-text);
            padding: 16px 24px 6px;
            font-weight: 700;
        }

        /* Main Content Wrapper */
        #main-wrapper {
            margin-right: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Header Bar */
        .top-navbar {
            background: var(--color-card-bg);
            height: 70px;
            padding: 0 30px;
            border-bottom: 1px solid var(--color-card-border);
            box-shadow: var(--shadow-card);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .user-badge {
            background: var(--color-warning-bg);
            color: var(--color-warning-text);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid var(--color-warning-border);
        }

        /* Cards & UI Elements */
        .card-custom {
            border: 1px solid var(--color-card-border);
            border-radius: 12px;
            background: var(--color-card-bg);
            box-shadow: var(--shadow-card);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-custom:hover {
            box-shadow: var(--shadow-card-hover);
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--color-brand-navy);
            font-weight: 700;
            border-radius: 8px;
            padding: 8px 18px;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: var(--color-brand-navy);
        }

        /* Pagination Styling */
        .pagination {
            margin-bottom: 0;
            gap: 4px;
            justify-content: center;
        }
        .pagination .page-item .page-link {
            border-radius: 6px !important;
            color: var(--color-brand-navy);
            border-color: var(--color-card-border);
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 600;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--color-brand-navy);
            font-weight: 700;
        }
        .pagination svg, nav svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            display: inline-block !important;
            vertical-align: middle;
        }

        @media (max-width: 991px) {
            #sidebar {
                right: -260px;
            }
            #sidebar.show {
                right: 0;
            }
            #main-wrapper {
                margin-right: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="brand-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div style="background: var(--primary-color); color: var(--color-brand-navy); width: 40px; height: 40px; border-radius: 10px; box-shadow: var(--shadow-gold);" class="d-flex align-items-center justify-content-center fw-bold fs-4">
                    JAC
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold">نظام التأجير</h6>
                    <small class="text-white-50 fs-7">JAC Rental Portal</small>
                </div>
            </div>
        </div>

        <div class="py-3">
            @php $currentRoute = Route::currentRouteName(); @endphp

            <div class="nav-category">الرئيسية</div>
            <a href="{{ route('dashboard.home') }}" class="nav-link {{ $currentRoute == 'dashboard.home' ? 'active' : '' }}">
                <i class="ti ti-dashboard"></i>
                <span>اللوحة الرئيسية</span>
            </a>

            @if(session('dashboard_user') && session('dashboard_user')->isAdmin())
                <div class="nav-category">إدارة المنصّة</div>
                <a href="{{ route('dashboard.orgs.list') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.orgs') ? 'active' : '' }}">
                    <i class="ti ti-building-store"></i>
                    <span>منظمات التأجير</span>
                </a>
            @else
                <div class="nav-category">المنظمة</div>
                <a href="{{ route('dashboard.orgs.show', session('dashboard_user')->org_id ?? 1) }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.orgs') ? 'active' : '' }}">
                    <i class="ti ti-building-store"></i>
                    <span>بيانات المنظمة</span>
                </a>
            @endif

            <a href="{{ route('dashboard.bookings.list') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.bookings') ? 'active' : '' }}">
                <i class="ti ti-receipt"></i>
                <span>الحجوزات</span>
            </a>

            <a href="{{ route('dashboard.finance.overview') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.finance') ? 'active' : '' }}">
                <i class="ti ti-report-money"></i>
                <span>المالية والتقارير</span>
            </a>

            @if(session('dashboard_user') && session('dashboard_user')->isAdmin())
                <div class="nav-category">الإعدادات العامة</div>
                <a href="{{ route('dashboard.settings.index') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.settings') ? 'active' : '' }}">
                    <i class="ti ti-settings"></i>
                    <span>التصنيفات والمواقع</span>
                </a>

                <a href="{{ route('dashboard.activity-log') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.activity-log') ? 'active' : '' }}">
                    <i class="ti ti-history"></i>
                    <span>سجل النشاطات</span>
                </a>
            @endif
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebar-toggle">
                    <i class="ti ti-menu-2 fs-4"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page-title', 'لوحة تحكم التأجير')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                @if(session('dashboard_user'))
                    <span class="user-badge d-none d-md-inline-block">
                        <i class="ti ti-user-check me-1"></i>
                        {{ session('dashboard_user')->name }} 
                        ({{ session('dashboard_user')->isAdmin() ? 'مدير نظام' : (session('dashboard_user')->isOwner() ? 'مالك منظمة' : 'موظف') }})
                    </span>
                @endif

                <form action="{{ route('dashboard.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="ti ti-logout me-1"></i> خروج
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="ti ti-circle-check me-2 fs-5"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="ti ti-alert-triangle me-2 fs-5"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white py-3 px-4 text-center border-top text-muted fs-7">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} - نظام التأجير المستقل (JAC)
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @yield('scripts')
</body>
</html>
