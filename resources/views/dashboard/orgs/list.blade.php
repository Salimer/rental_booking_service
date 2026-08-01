@extends('dashboard.layout')

@section('title', 'منظمات التأجير - نظام التأجير')
@section('page-title', 'إدارة منظمات التأجير')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1">منظمات التأجير والإيواء</h4>
        <p class="text-muted mb-0">قائمة المنظمات المزودة للخدمة وإدارة تفاصيل كل منظمة</p>
    </div>
    <a href="{{ route('dashboard.orgs.create') }}" class="btn btn-primary-custom">
        <i class="ti ti-plus me-1"></i> إضافة منظمة جديدة
    </a>
</div>

<!-- Search and Filter Bar -->
<div class="card-custom p-3 mb-4">
    <form action="{{ route('dashboard.orgs.list') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="ابحث باسم المنظمة، الكود، المدينة..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطلة</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الموافقة</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary-custom px-4">تصفية</button>
            <a href="{{ route('dashboard.orgs.list') }}" class="btn btn-light">إعادة ضبط</a>
        </div>
    </form>
</div>

<!-- Orgs List Table -->
<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light fs-7">
                <tr>
                    <th>المنظمة</th>
                    <th>مالك المنظمة</th>
                    <th>المدينة</th>
                    <th>العقارات والوحدات</th>
                    <th>الحجوزات</th>
                    <th>نسبة العمولة</th>
                    <th>الحالة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orgs as $org)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $org->name_ar }}</div>
                            <small class="text-muted">رمز المنظمة: {{ $org->code ?? '#'.$org->id }}</small>
                        </td>
                        <td>
                            @if($org->dashboardUser)
                                <div class="fw-semibold">{{ $org->dashboardUser->name }}</div>
                                <small class="text-muted">{{ $org->dashboardUser->email }}</small>
                            @else
                                <span class="text-muted">غير معين</span>
                            @endif
                        </td>
                        <td>{{ $org->city ?? 'غير محدد' }}</td>
                        <td>
                            <span class="badge bg-info-subtle text-info me-1">{{ $org->properties_count }} عقار</span>
                            <span class="badge bg-warning-subtle text-warning">{{ $org->units_count }} وحدة</span>
                        </td>
                        <td class="fw-bold">{{ number_format($org->bookings_count) }}</td>
                        <td class="fw-bold text-success">{{ number_format($org->commission, 2) }}%</td>
                        <td>
                            @php
                                $statusBadges = [
                                    'active' => 'bg-success-subtle text-success',
                                    'inactive' => 'bg-secondary-subtle text-secondary',
                                    'pending' => 'bg-warning-subtle text-warning',
                                    'suspended' => 'bg-danger-subtle text-danger',
                                ];
                                $statusLabels = [
                                    'active' => 'نشطة',
                                    'inactive' => 'معطلة',
                                    'pending' => 'قيد الانتظار',
                                    'suspended' => 'موقوفة',
                                ];
                            @endphp
                            <span class="badge badge-status {{ $statusBadges[$org->status] ?? 'bg-light' }}">
                                {{ $statusLabels[$org->status] ?? $org->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.orgs.show', $org->id) }}" class="btn btn-sm btn-primary-custom">
                                <i class="ti ti-eye me-1"></i> العرض الشامل
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">لا توجد منظمات مطابقة لشروط البحث.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orgs->links() }}
    </div>
</div>

@endsection
