@extends('dashboard.layout')

@section('title', 'تقويم الإتاحة والافتلاق - ' . $unit->name_ar)
@section('page-title', 'تقويم الإتاحة')

@section('content')

<div class="mb-4">
    <a href="{{ route('dashboard.orgs.show', $unit->property->org_id) }}" class="text-decoration-none text-muted">
        <i class="ti ti-arrow-right me-1"></i> العودة لمنظمة {{ $unit->property->org->name_ar ?? '' }}
    </a>
    <h4 class="fw-bold mt-2 mb-1">وحدة الإيواء: {{ $unit->name_ar }}</h4>
    <p class="text-muted mb-0">العقار: {{ $unit->property->title_ar ?? '' }} | السعر/ليلة: <strong>{{ number_format($unit->base_price, 2) }} ر.س</strong></p>
</div>

<div class="row g-4">
    <!-- Manual Date Lock Form -->
    <div class="col-lg-4">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="ti ti-lock me-2 text-danger"></i>حظر / إغلاق تواريخ يدوياً</h5>
            <p class="text-muted fs-7 mb-3">يمكنك إغلاق تواريخ محددة للصيانة أو الحجز الخارجي لمنع أي حجز عبر التطبيق</p>

            <form action="{{ route('dashboard.units.lock-dates', $unit->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7">من تاريخ (وصول)</label>
                    <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7">إلى تاريخ (مغادرة)</label>
                    <input type="date" name="end_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">سبب الإغلاق</label>
                    <input type="text" name="reason" class="form-control" placeholder="صيانة دورية / حجز مباشر">
                </div>

                <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                    <i class="ti ti-lock-check me-1"></i> حظر التواريخ المحددة
                </button>
            </form>
        </div>
    </div>

    <!-- Active Locks and Bookings List -->
    <div class="col-lg-8">
        <div class="card-custom p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="ti ti-lock-access me-2 text-warning"></i>التواريخ المغلقة يدوياً</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>من تاريخ</th>
                            <th>إلى تاريخ</th>
                            <th>السبب</th>
                            <th>تاريخ الإغلاق</th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($locks as $lock)
                            <tr>
                                <td class="fw-bold text-danger">{{ $lock->start_date }}</td>
                                <td class="fw-bold text-danger">{{ $lock->end_date }}</td>
                                <td>{{ $lock->reason ?? 'إغلاق يدوي' }}</td>
                                <td class="text-muted">{{ $lock->created_at ? $lock->created_at->format('Y-m-d H:i') : '' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">لا توجد تواريخ مغلقة يدوياً لهذه الوحدة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="ti ti-receipt me-2 text-info"></i>الحجوزات القادمة للوحدة</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>كود الحجز</th>
                            <th>اسم النزيل</th>
                            <th>تاريخ الوصول</th>
                            <th>تاريخ المغادرة</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($bookings as $b)
                            <tr>
                                <td class="fw-bold">#{{ $b->booking_code ?? $b->id }}</td>
                                <td>{{ $b->guest_name ?? $b->user->name ?? '' }}</td>
                                <td>{{ $b->check_in_date }}</td>
                                <td>{{ $b->check_out_date }}</td>
                                <td><span class="badge bg-success-subtle text-success">{{ $b->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">لا توجد حجوزات مؤكدة لهذه الوحدة حالياً.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
