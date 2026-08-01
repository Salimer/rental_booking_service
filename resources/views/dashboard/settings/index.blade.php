@extends('dashboard.layout')

@section('title', 'التصنيفات والمواقع - إعدادات المنظومة')
@section('page-title', 'التصنيفات والمواقع')

@section('content')

<div class="row g-4">
    <!-- Rental Types -->
    <div class="col-md-6">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="ti ti-category me-2 text-primary"></i>تصنيفات الإيواء (Types)</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                    <i class="ti ti-plus"></i> إضافة تصنيف
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>الاسم بالعربي</th>
                            <th>الاسم بالإنجليزي</th>
                            <th>الأيقونة</th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($types as $t)
                            <tr>
                                <td class="fw-bold">{{ $t->name_ar }}</td>
                                <td>{{ $t->name_en }}</td>
                                <td><i class="{{ $t->icon ?? 'ti-home' }} fs-5"></i></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">لا توجد تصنيفات مضافة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Amenities -->
    <div class="col-md-6">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="ti ti-star me-2 text-warning"></i>المرافق والخدمات (Amenities)</h5>
                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#addAmenityModal">
                    <i class="ti ti-plus"></i> إضافة مرفق
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>اسم المرفق</th>
                            <th>بالإنجليزي</th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($amenities as $am)
                            <tr>
                                <td class="fw-bold">{{ $am->name_ar }}</td>
                                <td>{{ $am->name_en }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center py-3 text-muted">لا توجد مرافق.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Locations (Countries, Cities & Neighborhoods) -->
    <div class="col-md-12">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h5 class="fw-bold mb-0"><i class="ti ti-map-pin me-2 text-danger"></i>دليل المواقع الجغرافية (الدول والمدن والأحياء)</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addCountryModal">
                        <i class="ti ti-plus"></i> إضافة دولة
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addCityModal">
                        <i class="ti ti-plus"></i> إضافة مدينة
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addNeighborhoodModal">
                        <i class="ti ti-plus"></i> إضافة حي
                    </button>
                </div>
            </div>

            <!-- Countries List -->
            <div class="mb-4">
                <h6 class="fw-bold text-muted mb-2"><i class="ti ti-world me-1"></i>الدول المسجلة:</h6>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($countries as $c)
                        <span class="badge bg-success-subtle text-success p-2 fs-7">
                            <i class="ti ti-flag me-1"></i>{{ $c->name_ar }} ({{ $c->cities_count }} مدن)
                        </span>
                    @empty
                        <span class="text-muted fs-7">لا توجد دول مسجلة بعد.</span>
                    @endforelse
                </div>
            </div>

            <!-- Cities & Neighborhoods List -->
            <h6 class="fw-bold text-muted mb-2"><i class="ti ti-building-community me-1"></i>المدن والأحياء التابعة:</h6>
            <div class="row g-3">
                @forelse($cities as $city)
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 bg-light-subtle">
                            <h6 class="fw-bold mb-2 text-dark"><i class="ti ti-building-community me-1"></i>{{ $city->name_ar }}</h6>
                            <span class="badge bg-secondary-subtle text-dark fs-7 mb-2">{{ $city->neighborhoods_count }} أحياء تابعة</span>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted fs-7">لا توجد مدن مسجلة.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Type -->
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.settings.types.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">إضافة تصنيف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم التصنيف (بالعربي)</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="فنادق / شقق مفروشة / شاليهات">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم التصنيف (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Hotels / Apartments">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary-custom">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Amenity -->
<div class="modal fade" id="addAmenityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.settings.amenities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">إضافة مرفق جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم المرفق (بالعربي)</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="واي فاي مجاني / مسبح / موقف سيارات">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم المرفق (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Free Wi-Fi / Pool">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary-custom">إضافة المرفق</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add City -->
<div class="modal fade" id="addCityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.settings.cities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">إضافة مدينة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم المدينة (بالعربي)</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="صنعاء / عدن / إب / تعز">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم المدينة (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Sanaa / Aden">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary-custom">إضافة المدينة</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Neighborhood -->
<div class="modal fade" id="addNeighborhoodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.settings.neighborhoods.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">إضافة حي جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اختر المدينة التابع لها</label>
                        <select name="city_id" class="form-select" required>
                            <option value="">اختر المدينة...</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم الحي (بالعربي)</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="حدة / الأصبحي / خور مكسر">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary-custom">إضافة الحي</button>
                </div>
            </form>
        </div>
    </div>
<!-- Modal: Add Country -->
<div class="modal fade" id="addCountryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.settings.countries.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">إضافة دولة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم الدولة (بالعربي)</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="اليمن / المملكة العربية السعودية">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">اسم الدولة (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Yemen / Saudi Arabia">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary-custom">إضافة الدولة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
