@extends('dashboard.layout')

@section('title', 'كشف حساب المنظمة - ' . $org->name_ar)
@section('page-title', 'الحسابات المالية للمنظمة')

@section('content')

<!-- Back Link -->
<div class="mb-3">
    <a href="{{ route('dashboard.finance.overview') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-right me-1"></i> العودة إلى نظرة عامة على المالية
    </a>
</div>

<!-- Org Profile Strip -->
<div class="card-custom p-4 mb-4 bg-primary-subtle border-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            @if($org->logo)
                <img src="{{ asset($org->logo) }}" class="rounded-3 border" style="width: 60px; height: 60px; object-fit: cover; background: #fff;">
            @else
                <div style="width: 60px; height: 60px; border-radius: 12px;" class="bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-3">
                    <i class="ti ti-building-store"></i>
                </div>
            @endif
            <div>
                <h4 class="fw-bold mb-1 text-dark">{{ $org->name_ar }}</h4>
                <div class="d-flex flex-wrap gap-3 fs-7 text-muted">
                    <span>الكود: <strong>{{ $org->code }}</strong></span>
                    <span>العمولة: <strong class="text-primary">{{ number_format($org->commission, 1) }}%</strong></span>
                    <span>العملة المفضلة: <strong>{{ $org->preferred_currency ?? 'SAR' }}</strong></span>
                    <span>الحالة: <span class="badge bg-success-subtle text-success">{{ $org->status }}</span></span>
                </div>
            </div>
        </div>

        <a href="{{ route('dashboard.orgs.show', $org->id) }}" class="btn btn-sm btn-primary-custom">
            <i class="ti ti-building me-1"></i> ملف المنظمة والعقارات
        </a>
    </div>
</div>

<!-- Date Filter Form -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ route('dashboard.finance.org', $org->id) }}" class="row g-2 align-items-center">
        <div class="col-auto">
            <span class="fw-bold fs-7 text-muted me-2"><i class="ti ti-filter me-1"></i>تصفية الفترات:</span>
        </div>
        <div class="col-auto">
            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}" placeholder="من تاريخ">
        </div>
        <div class="col-auto">
            <span class="fs-7 text-muted">إلى</span>
        </div>
        <div class="col-auto">
            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}" placeholder="إلى تاريخ">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary-custom">
                <i class="ti ti-search me-1"></i> تصفية
            </button>
            @if($fromDate || $toDate)
                <a href="{{ route('dashboard.finance.org', $org->id) }}" class="btn btn-sm btn-light ms-1">إلغاء التصفية</a>
            @endif
        </div>
    </form>
</div>

<!-- Totals by Currency -->
@if(!empty($totalsByCurrency) && count($totalsByCurrency) > 0)
    <div class="row g-3 mb-4">
        @foreach($totalsByCurrency as $curr => $row)
            <div class="col-md-6 col-lg-3">
                <div class="card-custom p-3 border-start border-4 border-success">
                    <span class="badge bg-success-subtle text-success mb-2">{{ $curr }}</span>
                    <div class="mb-2">
                        <span class="fs-7 text-muted d-block">مبيعات المنظمة</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ number_format($row->total_revenue, 2) }} <small class="fs-7">{{ $curr }}</small></h5>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2 fs-7">
                        <div>
                            <span class="text-muted d-block">عمولة JAC</span>
                            <strong class="text-primary">{{ number_format($row->jac_earned, 2) }}</strong>
                        </div>
                        <div class="text-end">
                            <span class="text-muted d-block">مستحقات المزود</span>
                            <strong class="text-success">{{ number_format($row->org_payouts, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Booking Ledger Table -->
<div class="card-custom p-4">
    <h5 class="fw-bold mb-3"><i class="ti ti-receipt me-2 text-primary"></i>دفتر أستاذ الحجوزات المدفوعة (Booking Ledger)</h5>
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light fs-7">
                <tr>
                    <th>رقم الحجز</th>
                    <th>العقار والوحدة</th>
                    <th>تواريخ الإقامة</th>
                    <th>المبلغ المدفوع</th>
                    <th>عمولة JAC</th>
                    <th>مستحق المزود</th>
                    <th>العملة</th>
                </tr>
            </thead>
            <tbody class="fs-7">
                @forelse($bookings as $b)
                    @php
                        $tx = $b->transaction;
                        $totalPaid = $tx ? (float)$tx->total_amount : (float)($b->unit_price * $b->nights_count);
                        $jacCommission = ($tx && $tx->admin_commission > 0) ? (float)$tx->admin_commission : ($totalPaid * ($org->commission / 100));
                        $orgShare = $totalPaid - $jacCommission;
                        $curr = $tx ? $tx->currency : ($b->currency ?? 'SAR');
                    @endphp
                    <tr>
                        <td class="fw-bold">
                            <a href="{{ route('dashboard.bookings.show', $b->id) }}" class="text-decoration-none">
                                #{{ $b->booking_code ?? $b->reference_no ?? $b->id }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $b->property->title_ar ?? '' }}</div>
                            <small class="text-muted">{{ $b->unit->name_ar ?? '' }}</small>
                        </td>
                        <td>
                            <div>{{ $b->check_in_date }} &rarr; {{ $b->check_out_date }}</div>
                            <small class="text-muted">{{ $b->nights_count }} ليلة</small>
                        </td>
                        <td class="fw-bold">{{ number_format($totalPaid, 2) }}</td>
                        <td class="fw-bold text-primary">{{ number_format($jacCommission, 2) }}</td>
                        <td class="fw-bold text-success">{{ number_format($orgShare, 2) }}</td>
                        <td><span class="badge bg-secondary-subtle text-dark">{{ $curr }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد حجوزات مدفوعة مسجلة لهذه المنظمة في هذه الفترة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
</div>

@endsection
