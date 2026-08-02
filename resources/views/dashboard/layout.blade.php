@php 
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar' || str_starts_with($locale, 'ar');
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
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

        /* Dynamic LTR / RTL Sidebar Layout */
        html[dir="rtl"] #sidebar {
            right: 0;
            left: auto;
        }
        html[dir="rtl"] #main-wrapper {
            margin-right: 260px;
            margin-left: 0;
            transition: all 0.3s ease;
        }
        body.sidebar-collapsed #main-wrapper {
            margin-right: 76px !important;
            margin-left: 0 !important;
        }

        html[dir="ltr"] #sidebar {
            left: 0;
            right: auto;
        }
        html[dir="ltr"] #main-wrapper {
            margin-left: 260px;
            margin-right: 0;
            transition: all 0.3s ease;
        }
        body.sidebar-collapsed html[dir="ltr"] #main-wrapper {
            margin-left: 76px !important;
            margin-right: 0 !important;
        }

        /* Direction-Aware Sidebar Toggle Alignment */
        /* Arabic (RTL): Aligned to the LEFT */
        html[dir="rtl"] .sidebar-toggle-container {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        /* English (LTR): Aligned to the RIGHT */
        html[dir="ltr"] .sidebar-toggle-container {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        /* Sidebar Collapsed State (Desktop & Tablet) */
        body.sidebar-collapsed #sidebar {
            width: 76px;
        }

        body.sidebar-collapsed #sidebar .brand-text-wrapper .brand-text,
        body.sidebar-collapsed #sidebar .nav-text,
        body.sidebar-collapsed #sidebar .nav-category {
            display: none !important;
        }

        body.sidebar-collapsed #sidebar .brand-header {
            padding: 16px 12px;
            align-items: center !important;
            justify-content: center !important;
        }

        body.sidebar-collapsed #sidebar .sidebar-toggle-container {
            justify-content: center !important;
        }

        body.sidebar-collapsed #sidebar .nav-link {
            justify-content: center;
            padding: 12px;
            margin: 4px 10px;
        }

        body.sidebar-collapsed #sidebar .nav-link i {
            margin: 0;
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
        <div class="brand-header d-flex flex-column align-items-start justify-content-center py-3 px-3 border-bottom">
            <div class="d-flex align-items-center gap-2 brand-text-wrapper overflow-hidden mb-2">
                <div style="background: var(--primary-color); color: var(--color-brand-navy); width: 42px; height: 42px; border-radius: 10px; box-shadow: var(--shadow-gold);" class="d-flex align-items-center justify-content-center fw-bold fs-4 shrink-0">
                    JAC
                </div>
                <div class="brand-text">
                    <h6 class="mb-0 text-white fw-bold text-nowrap">نظام التأجير</h6>
                    <small class="text-white-50 fs-7 text-nowrap">JAC Rental Portal</small>
                </div>
            </div>
            <div class="sidebar-toggle-container mt-1">
                <button type="button" class="btn btn-link text-white-50 p-0 border-0 shadow-none" id="sidebar-desktop-toggle" title="طي / توسيع القائمة الجانبية">
                    <i class="ti {{ $isRtl ? 'ti-layout-sidebar-right-collapse' : 'ti-layout-sidebar-left-collapse' }} fs-4" id="sidebar-collapse-icon"></i>
                </button>
            </div>
        </div>

        <div class="py-3">
            @php $currentRoute = Route::currentRouteName(); @endphp

            <div class="nav-category">الرئيسية</div>
            <a href="{{ route('dashboard.home') }}" class="nav-link {{ $currentRoute == 'dashboard.home' ? 'active' : '' }}" title="اللوحة الرئيسية">
                <i class="ti ti-dashboard"></i>
                <span class="nav-text">اللوحة الرئيسية</span>
            </a>

            @if(session('dashboard_user') && session('dashboard_user')->isAdmin())
                <div class="nav-category">إدارة المنصّة</div>
                <a href="{{ route('dashboard.orgs.list') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.orgs') ? 'active' : '' }}" title="منظمات التأجير">
                    <i class="ti ti-building-store"></i>
                    <span class="nav-text">منظمات التأجير</span>
                </a>
            @else
                <div class="nav-category">المنظمة</div>
                <a href="{{ route('dashboard.orgs.show', session('dashboard_user')->org_id ?? 1) }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.orgs') ? 'active' : '' }}" title="بيانات المنظمة">
                    <i class="ti ti-building-store"></i>
                    <span class="nav-text">بيانات المنظمة</span>
                </a>
            @endif

            <a href="{{ route('dashboard.bookings.list') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.bookings') ? 'active' : '' }}" title="الحجوزات">
                <i class="ti ti-receipt"></i>
                <span class="nav-text">الحجوزات</span>
            </a>

            <a href="{{ route('dashboard.finance.overview') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.finance') ? 'active' : '' }}" title="المالية والتقارير">
                <i class="ti ti-report-money"></i>
                <span class="nav-text">المالية والتقارير</span>
            </a>

            @if(session('dashboard_user') && session('dashboard_user')->isAdmin())
                <div class="nav-category">الإعدادات العامة</div>
                <a href="{{ route('dashboard.settings.index') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.settings') ? 'active' : '' }}" title="التصنيفات والمواقع">
                    <i class="ti ti-settings"></i>
                    <span class="nav-text">التصنيفات والمواقع</span>
                </a>

                <a href="{{ route('dashboard.activity-log') }}" class="nav-link {{ str_contains($currentRoute, 'dashboard.activity-log') ? 'active' : '' }}" title="سجل النشاطات">
                    <i class="ti ti-history"></i>
                    <span class="nav-text">سجل النشاطات</span>
                </a>
            @endif
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light" id="sidebar-toggle" title="طي / توسيع القائمة">
                    <i class="ti {{ $isRtl ? 'ti-layout-sidebar-right-collapse' : 'ti-layout-sidebar-left-collapse' }} fs-4" id="top-toggle-icon"></i>
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
            @if(session('impersonator_id'))
                <div class="alert alert-warning border border-warning shadow-sm rounded-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2 py-2 px-3">
                    <div class="d-flex align-items-center gap-2 text-dark fw-bold">
                        <i class="ti ti-user-check fs-4 text-warning"></i>
                        <span>أنت الآن تتصفح النظام مؤقتاً بحساب: <u class="text-primary">{{ session('dashboard_user')->name ?? 'المستخدم' }}</u> ({{ session('dashboard_user')->email ?? '' }})</span>
                    </div>
                    <form action="{{ route('dashboard.impersonate.stop') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-dark fw-semibold">
                            <i class="ti ti-logout-2 me-1"></i> العودة لحساب مدير النظام
                        </button>
                    </form>
                </div>
            @endif

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
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const sidebar = document.getElementById('sidebar');
            const desktopToggleBtn = document.getElementById('sidebar-desktop-toggle');
            const mobileToggleBtn = document.getElementById('sidebar-toggle');
            const collapseIcon = document.getElementById('sidebar-collapse-icon');
            const topToggleIcon = document.getElementById('top-toggle-icon');

            const isRtl = document.documentElement.dir === 'rtl';

            // Restore stored user preference for collapsed state
            if (localStorage.getItem('sidebar_collapsed') === 'true') {
                body.classList.add('sidebar-collapsed');
                updateIcons(true);
            }

            function updateIcons(isCollapsed) {
                let iconClass;
                if (isRtl) {
                    iconClass = isCollapsed ? 'ti-layout-sidebar-right-expand' : 'ti-layout-sidebar-right-collapse';
                } else {
                    iconClass = isCollapsed ? 'ti-layout-sidebar-left-expand' : 'ti-layout-sidebar-left-collapse';
                }
                if (collapseIcon) {
                    collapseIcon.className = `ti ${iconClass} fs-4`;
                }
                if (topToggleIcon) {
                    topToggleIcon.className = `ti ${iconClass} fs-4`;
                }
            }

            function toggleSidebar() {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle('show');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = body.classList.contains('sidebar-collapsed');
                    localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
                    updateIcons(isCollapsed);
                }
            }

            desktopToggleBtn?.addEventListener('click', toggleSidebar);
            mobileToggleBtn?.addEventListener('click', toggleSidebar);
        });
    </script>
    @yield('scripts')
</body>
</html>
