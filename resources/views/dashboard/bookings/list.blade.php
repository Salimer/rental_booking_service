@extends('dashboard.layout')

@section('title', 'إدارة الحجوزات - نظام التأجير')
@section('page-title', 'سجل وإدارة الحجوزات')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1">سجل ومتابعة الحجوزات</h4>
        <p class="text-muted mb-0">عرض جميع حجوزات العقارات والوحدات وتحديث حالات الوصول والإنهاء</p>
    </div>
</div>

<!-- Search & Status Filter Bar -->
<div class="card-custom p-3 mb-4">
    <form action="{{ route('dashboard.bookings.list') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="ابحث بكود الحجز، اسم العميل، الهاتف..." value="{{ request('search') }}">
            </div>
        </div>

        @if($user->isAdmin())
            <div class="col-md-3">
                <select name="org_id" class="form-select">
                    <option value="">جميع المنظمات</option>
                    @foreach($orgs as $org)
                        <option value="{{ $org->id }}" {{ request('org_id') == $org->id ? 'selected' : '' }}>{{ $org->name_ar }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">جميع الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>تم الدخول (Check-in)</option>
                <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>تم المغادرة (Check-out)</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary-custom w-100">تصفية</button>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="card-custom p-4">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light fs-7">
                <tr>
                    <th>كود الحجز</th>
                    <th>المنظمة والعقار</th>
                    <th>الوحدة</th>
                    <th>بيانات العميل</th>
                    <th>تاريخ الوصول / المغادرة</th>
                    <th>المبلغ الإجمالي</th>
                    <th>الحالة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="fs-7">
                @forelse($bookings as $b)
                    <tr>
                        <td class="fw-bold text-primary">#{{ $b->booking_code ?? $b->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $b->org->name_ar ?? '' }}</div>
                            <small class="text-muted">{{ $b->property->title_ar ?? '' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $b->unit->name_ar ?? $b->unit->name ?? 'وحدة' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $b->guest_name ?? $b->user->name ?? 'زائر' }}</div>
                            <small class="text-muted">{{ $b->guest_phone ?? $b->user->phone ?? '' }}</small>
                        </td>
                        <td>
                            <div><i class="ti ti-calendar-event me-1 text-success"></i>{{ $b->check_in_date }}</div>
                            <small class="text-muted"><i class="ti ti-calendar-minus me-1 text-danger"></i>{{ $b->check_out_date }}</small>
                        </td>
                        <td class="fw-bold text-success fs-6">{{ number_format($b->total_price, 2) }} ر.س</td>
                        <td>
                            @php
                                $statusBadges = [
                                    'pending' => 'bg-warning-subtle text-warning',
                                    'confirmed' => 'bg-info-subtle text-info',
                                    'checked_in' => 'bg-primary-subtle text-primary',
                                    'checked_out' => 'bg-secondary-subtle text-secondary',
                                    'completed' => 'bg-success-subtle text-success',
                                    'cancelled' => 'bg-danger-subtle text-danger',
                                ];
                                $statusLabels = [
                                    'pending' => 'قيد الانتظار',
                                    'confirmed' => 'مؤكد',
                                    'checked_in' => 'تم الدخول',
                                    'checked_out' => 'تم المغادرة',
                                    'completed' => 'مكتمل',
                                    'cancelled' => 'ملغي',
                                ];
                            @endphp
                            <span class="badge badge-status {{ $statusBadges[$b->status] ?? 'bg-light' }}">
                                {{ $statusLabels[$b->status] ?? $b->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.bookings.show', $b->id) }}" class="btn btn-sm btn-primary-custom">
                                <i class="ti ti-eye me-1"></i> التفاصيل
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">لا توجد حجوزات مطابقة لشروط البحث.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>

@endsection
