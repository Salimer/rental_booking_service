@extends('dashboard.layout')

@section('title', 'تفاصيل الحجز - #' . ($booking->booking_code ?? $booking->id))
@section('page-title', 'تفاصيل الحجز #' . ($booking->booking_code ?? $booking->id))

@section('content')

<div class="mb-3">
    <a href="{{ route('dashboard.bookings.list') }}" class="text-decoration-none text-muted">
        <i class="ti ti-arrow-right me-1"></i> العودة لقائمة الحجوزات
    </a>
</div>

<div class="row g-4">
    <!-- Booking Details Card -->
    <div class="col-lg-8">
        <div class="card-custom p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="fw-bold mb-0">بيانات الحجز والإقامة</h5>
                <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">
                    كود الحجز: #{{ $booking->booking_code ?? $booking->id }}
                </span>
            </div>
            <hr class="text-muted opacity-25 mb-4">

            <div class="row g-3 mb-4 fs-7">
                <div class="col-md-6">
                    <span class="text-muted d-block mb-1">المنظمة:</span>
                    <strong class="fs-6">{{ $booking->org->name_ar ?? '' }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block mb-1">العقار:</span>
                    <strong class="fs-6">{{ $booking->property->title_ar ?? '' }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block mb-1">وحدة الإيواء:</span>
                    <strong class="fs-6 text-primary">{{ $booking->unit->name_ar ?? '' }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block mb-1">تاريخ الإنشاء:</span>
                    <strong>{{ $booking->created_at ? $booking->created_at->format('Y-m-d H:i') : '-' }}</strong>
                </div>
            </div>

            <div class="p-3 border rounded-3 bg-light-subtle row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted fs-7 d-block">تاريخ الوصول (Check-in):</span>
                    <strong class="text-success fs-6"><i class="ti ti-calendar-event me-1"></i>{{ $booking->check_in_date }}</strong>
                </div>
                <div class="col-md-4">
                    <span class="text-muted fs-7 d-block">تاريخ المغادرة (Check-out):</span>
                    <strong class="text-danger fs-6"><i class="ti ti-calendar-minus me-1"></i>{{ $booking->check_out_date }}</strong>
                </div>
                <div class="col-md-4">
                    <span class="text-muted fs-7 d-block">عدد الليالي:</span>
                    <strong class="fs-6">{{ $booking->total_nights ?? 1 }} ليلة</strong>
                </div>
            </div>

            <h6 class="fw-bold mb-3"><i class="ti ti-user me-2 text-info"></i>بيانات النزيل والعميل</h6>
            <div class="row g-3 fs-7 mb-4">
                <div class="col-md-6">
                    <span class="text-muted d-block mb-1">اسم النزيل:</span>
                    <strong class="fs-6">{{ $booking->guest_name ?? $booking->user->name ?? 'غير محدد' }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block mb-1">رقم الهاتف:</span>
                    <strong class="fs-6">{{ $booking->guest_phone ?? $booking->user->phone ?? 'غير محدد' }}</strong>
                </div>
            </div>

            <h6 class="fw-bold mb-3"><i class="ti ti-calculator me-2 text-success"></i>الملخص المالي</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle fs-7 mb-0">
                    <tbody>
                        <tr>
                            <td>سعر الليلة الأساسي:</td>
                            <td class="fw-bold text-end">{{ number_format($booking->price_per_night ?? 0, 2) }} ر.س</td>
                        </tr>
                        <tr>
                            <td>رسوم الخدمة والعمولة:</td>
                            <td class="fw-bold text-end">{{ number_format($booking->service_fee ?? 0, 2) }} ر.س</td>
                        </tr>
                        <tr class="table-success">
                            <td class="fw-bold fs-6">إجمالي المبلغ الدفوع:</td>
                            <td class="fw-bold text-end fs-6 text-success">{{ number_format($booking->total_price ?? 0, 2) }} ر.س</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions & Status Sidebar -->
    <div class="col-lg-4">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="ti ti-adjustments me-2 text-primary"></i>تحديث حالة الحجز</h5>
            <p class="text-muted fs-7 mb-3">حالة الحجز الحالية: <strong class="text-primary">{{ $booking->status }}</strong></p>

            <form action="{{ route('dashboard.bookings.update-status', $booking->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7">اختر الحالة الجديدة</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>قيد الانتظار (Pending)</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>مؤكد (Confirmed)</option>
                        <option value="checked_in" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>تم دخول النزيل (Check-in)</option>
                        <option value="checked_out" {{ $booking->status == 'checked_out' ? 'selected' : '' }}>تم مغادرة النزيل (Check-out)</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>مكتمل (Completed)</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>إلغاء الحجز (Cancelled)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7">ملاحظات التغيير</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="أدخل سبب التغيير أو ملاحظات الدخول..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold">
                    حفظ وتحديث الحالة
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
