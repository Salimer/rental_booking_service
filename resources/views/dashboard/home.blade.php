@extends('dashboard.layout')

@section('title', 'اللوحة الرئيسية - نظام التأجير')
@section('page-title', 'اللوحة الرئيسية')

@section('content')

<!-- Header Welcome Banner -->
<div class="card-custom brand-welcome-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">أهلاً بك، {{ $user->name }} 👋</h4>
            <p class="mb-0 text-white-50 fs-6">
                {{ $user->isAdmin() ? 'مدير المنصّة المركزية لنظام التأجير' : 'لوحة تحكم إدارة وحدات وعقارات المنظمة' }}
            </p>
        </div>
        @if($user->isAdmin())
            <a href="{{ route('dashboard.orgs.create') }}" class="btn btn-light fw-bold text-dark rounded-pill px-4">
                <i class="ti ti-plus me-1"></i> إضافة منظمة جديدة
            </a>
        @endif
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    @if($user->isAdmin())
        <div class="col-md-6 col-lg-3">
            <div class="card-custom p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-7 fw-semibold">منظمات التأجير</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ number_format($totalOrgs) }}</h3>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="ti ti-building-store"></i>
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-6 col-lg-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted fs-7 fw-semibold">العقارات والمنشآت</span>
                <h3 class="fw-bold mb-0 mt-1">{{ number_format($totalProperties) }}</h3>
            </div>
            <div class="stat-icon bg-info-subtle text-info">
                <i class="ti ti-home"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted fs-7 fw-semibold">وحدات الإيواء</span>
                <h3 class="fw-bold mb-0 mt-1">{{ number_format($totalUnits) }}</h3>
            </div>
            <div class="stat-icon bg-warning-subtle text-warning">
                <i class="ti ti-door"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted fs-7 fw-semibold">إجمالي الحجوزات</span>
                <h3 class="fw-bold mb-0 mt-1">{{ number_format($totalBookings) }}</h3>
            </div>
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="ti ti-receipt"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted fs-7 fw-semibold">إجمالي المبيعات</span>
                <h3 class="fw-bold mb-0 mt-1 text-success">{{ number_format($revenueTotal, 2) }} <small class="fs-7">ر.س</small></h3>
            </div>
            <div class="stat-icon bg-success-subtle text-success">
                <i class="ti ti-cash"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Tables Section -->
<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="card-custom p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0"><i class="ti ti-clock-history me-2 text-primary"></i>أحدث الحجوزات</h5>
                <a href="{{ route('dashboard.bookings.list') }}" class="btn btn-link p-0 text-decoration-none fw-semibold">عرض الكل ←</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>رقم الحجز</th>
                            <th>العقار / الوحدة</th>
                            <th>العميل</th>
                            <th>تاريخ الوصول</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($recentBookings as $b)
                            <tr>
                                <td class="fw-bold">#{{ $b->booking_code ?? $b->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $b->property->title_ar ?? 'عقار غير معروف' }}</div>
                                    <small class="text-muted">{{ $b->unit->name_ar ?? '' }}</small>
                                </td>
                                <td>
                                    <div>{{ $b->guest_name ?? $b->user->name ?? 'زائر' }}</div>
                                    <small class="text-muted">{{ $b->guest_phone ?? '' }}</small>
                                </td>
                                <td>{{ $b->check_in_date ?? '-' }}</td>
                                <td class="fw-bold text-success">{{ number_format($b->total_price, 2) }} ر.س</td>
                                <td>
                                    @php
                                        $statusBadges = [
                                            'pending' => 'bg-warning-subtle text-warning',
                                            'confirmed' => 'bg-info-subtle text-info',
                                            'checked_in' => 'bg-primary-subtle text-primary',
                                            'completed' => 'bg-success-subtle text-success',
                                            'cancelled' => 'bg-danger-subtle text-danger',
                                        ];
                                        $statusNames = [
                                            'pending' => 'قيد الانتظار',
                                            'confirmed' => 'مؤكد',
                                            'checked_in' => 'تم الدخول',
                                            'completed' => 'مكتمل',
                                            'cancelled' => 'ملغي',
                                        ];
                                    @endphp
                                    <span class="badge badge-status {{ $statusBadges[$b->status] ?? 'bg-secondary' }}">
                                        {{ $statusNames[$b->status] ?? $b->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.bookings.show', $b->id) }}" class="btn btn-sm btn-light">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">لا توجد حجوزات مسجلة حديثاً.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Orgs Sidebar (Admin view) -->
    <div class="col-lg-4">
        <div class="card-custom p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0"><i class="ti ti-building-store me-2 text-success"></i>المنظمات النشطة</h5>
                @if($user->isAdmin())
                    <a href="{{ route('dashboard.orgs.list') }}" class="btn btn-link p-0 text-decoration-none fw-semibold">إدارة المنظمات ←</a>
                @endif
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse($recentOrgs as $org)
                    <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $org->name_ar }}</h6>
                            <div class="d-flex gap-2 fs-7 text-muted">
                                <span><i class="ti ti-home me-1"></i>{{ $org->properties_count }} عقار</span>
                                <span>•</span>
                                <span><i class="ti ti-receipt me-1"></i>{{ $org->bookings_count }} حجز</span>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.orgs.show', $org->id) }}" class="btn btn-sm btn-outline-success">
                            عرض
                        </a>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">لا توجد منظمات مسجلة.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
