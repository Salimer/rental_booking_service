@extends('dashboard.layout')

@section('title', 'المالية والتقارير - نظام التأجير')
@section('page-title', 'الإحصائيات والتقارير المالية')

@section('content')

<!-- Date Filter Form -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ route('dashboard.finance.overview') }}" class="row g-2 align-items-center">
        <div class="col-auto">
            <span class="fw-bold fs-7 text-muted me-2"><i class="ti ti-filter me-1"></i>تصفية الفترات المالية:</span>
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
                <a href="{{ route('dashboard.finance.overview') }}" class="btn btn-sm btn-light ms-1">إلغاء التصفية</a>
            @endif
        </div>
    </form>
</div>

<!-- Multi-Currency Summary Stat Cards -->
@if(!empty($totalsByCurrency) && count($totalsByCurrency) > 0)
    <div class="mb-4">
        <h6 class="fw-bold text-muted mb-3"><i class="ti ti-chart-pie me-1"></i>ملخص المبيعات وحصة المنصة حسب العملة:</h6>
        <div class="row g-3">
            @foreach($totalsByCurrency as $curr => $row)
                <div class="col-md-6 col-lg-3">
                    <div class="card-custom p-3 border-start border-4 border-primary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-bold fs-7">{{ $curr }}</span>
                            <span class="fs-7 text-muted">{{ $row->bookings_count }} حجز مدفوع</span>
                        </div>
                        <div class="mb-2">
                            <span class="fs-7 text-muted d-block">إجمالي المبيعات</span>
                            <h5 class="fw-bold mb-0 text-dark">{{ number_format($row->total_revenue, 2) }} <small class="fs-7">{{ $curr }}</small></h5>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2 fs-7">
                            <div>
                                <span class="text-muted d-block">عمولة JAC</span>
                                <strong class="text-primary">{{ number_format($row->jac_earned, 2) }}</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-muted d-block">مستحقات المنظمات</span>
                                <strong class="text-success">{{ number_format($row->org_payouts, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Per-Organization Breakdown Table -->
<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="ti ti-building-bank me-2 text-primary"></i>تفاصيل مستحقات منظمات التأجير (Organization Accounts)</h5>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light fs-7">
                <tr>
                    <th>اسم المنظمة</th>
                    <th>دورية السداد</th>
                    <th>الحجوزات المدفوعة</th>
                    <th>إجمالي المبيعات</th>
                    <th>نسبة العمولة</th>
                    <th>عمولة JAC</th>
                    <th>مستحقات المزود (Vendor Payout)</th>
                    <th class="text-end">إجراءات</th>
                </tr>
            </thead>
            <tbody class="fs-7">
                @forelse($orgsFinance as $f)
                    <tr>
                        <td class="fw-bold">
                            <a href="{{ route('dashboard.finance.org', $f->id) }}" class="text-dark text-decoration-none">
                                {{ $f->name_ar }}
                            </a>
                            <div class="fs-7 text-muted font-normal">{{ $f->code }}</div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark">
                                {{ $f->payout_frequency == 'daily' ? 'يومي' : ($f->payout_frequency == 'weekly' ? 'أسبوعي' : 'شهري') }}
                            </span>
                        </td>
                        <td>{{ number_format($f->paid_bookings_count ?? $f->bookings_count) }} حجز</td>
                        <td class="fw-bold">{{ number_format($f->total_sales, 2) }} {{ $f->currency }}</td>
                        <td>{{ number_format($f->commission, 1) }}%</td>
                        <td class="fw-bold text-primary">{{ number_format($f->app_commission, 2) }} {{ $f->currency }}</td>
                        <td class="fw-bold text-success fs-6">{{ number_format($f->vendor_payout, 2) }} {{ $f->currency }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.finance.org', $f->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-file-text me-1"></i> كشف الحساب
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">لا توجد بيانات مالية متوفرة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
