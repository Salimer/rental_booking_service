@extends('dashboard.layout')

@section('title', 'المالية والعمولات - نظام التأجير')
@section('page-title', 'المالية والتقارير')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card-custom p-4 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted fs-7 fw-semibold">إجمالي مبيعات التأجير</span>
                <h3 class="fw-bold mb-0 mt-1 text-success">{{ number_format($totalRevenue, 2) }} <small class="fs-7">ر.س</small></h3>
            </div>
            <div class="stat-icon bg-success-subtle text-success">
                <i class="ti ti-cash"></i>
            </div>
        </div>
    </div>
</div>

<!-- Finance Table -->
<div class="card-custom p-4">
    <h5 class="fw-bold mb-3"><i class="ti ti-report-money me-2 text-primary"></i>تفاصيل المبيعات والمبالغ المستحقة لكل منظمة</h5>
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light fs-7">
                <tr>
                    <th>اسم المنظمة</th>
                    <th>إجمالي الحجوزات</th>
                    <th>إجمالي مبيعات المنظمة</th>
                    <th>نسبة عمولة المنصة</th>
                    <th>عمولة المنصة (JAC)</th>
                    <th>مستحقات مزود الخدمة (Vendor Payout)</th>
                </tr>
            </thead>
            <tbody class="fs-7">
                @forelse($orgsFinance as $f)
                    <tr>
                        <td class="fw-bold">{{ $f->name_ar }}</td>
                        <td>{{ number_format($f->bookings_count) }} حجز</td>
                        <td class="fw-bold">{{ number_format($f->total_sales, 2) }} ر.س</td>
                        <td>{{ number_format($f->commission, 1) }}%</td>
                        <td class="fw-bold text-primary">{{ number_format($f->app_commission, 2) }} ر.س</td>
                        <td class="fw-bold text-success fs-6">{{ number_format($f->vendor_payout, 2) }} ر.س</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">لا توجد بيانات مالية متوفرة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
