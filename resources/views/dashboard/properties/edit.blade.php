@extends('dashboard.layout')

@section('title', 'تعديل العقار - ' . $property->title_ar)
@section('page-title', 'تعديل بيانات العقار والصور')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="ti ti-home-edit me-2 text-primary"></i>تعديل عقار: {{ $property->title_ar }}</h5>
                <a href="{{ route('dashboard.orgs.show', $property->org_id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> العودة للمنظمة
                </a>
            </div>

            <form action="{{ route('dashboard.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Info -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-info-circle me-1"></i>البيانات الأساسية</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">عنوان العقار (بالعربي) *</label>
                        <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $property->title_ar) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">عنوان العقار (بالإنجليزي)</label>
                        <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $property->title_en) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">وصف العقار (بالعربي)</label>
                        <textarea name="description_ar" class="form-control" rows="3">{{ old('description_ar', $property->description_ar) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">وصف العقار (بالإنجليزي)</label>
                        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $property->description_en) }}</textarea>
                    </div>
                </div>

                <!-- Attributes & Location -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-map-pin me-1"></i>التصنيف والموقع والحالة</h6>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">تصنيف الإيواء *</label>
                        <select name="type_id" class="form-select" required>
                            @foreach($types as $t)
                                <option value="{{ $t->id }}" {{ old('type_id', $property->type_id) == $t->id ? 'selected' : '' }}>{{ $t->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">الدولة</label>
                        <select name="country_id" id="editCountrySelect" class="form-select">
                            <option value="">اختر الدولة...</option>
                            @foreach($countries as $co)
                                <option value="{{ $co->id }}" {{ old('country_id', $property->country?->id ?? $property->city?->country_id) == $co->id ? 'selected' : '' }}>{{ $co->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">المدينة</label>
                        <select name="city_id" class="form-select" id="editCitySelect">
                            <option value="">اختر المدينة...</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}" data-country-id="{{ $c->country_id }}" {{ old('city_id', $property->city_id) == $c->id ? 'selected' : '' }}>{{ $c->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">الحي / المنطقة</label>
                        <select name="neighborhood_id" class="form-select" id="editNeighborhoodSelect">
                            <option value="">اختر الحي...</option>
                            @foreach($neighborhoods as $n)
                                <option value="{{ $n->id }}" {{ old('neighborhood_id', $property->neighborhood_id) == $n->id ? 'selected' : '' }}>{{ $n->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold fs-7">تقييم النجوم (Star Rating)</label>
                        <select name="star_rating" class="form-select">
                            <option value="">بدون تقييم</option>
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ old('star_rating', $property->star_rating) == $i ? 'selected' : '' }}>{{ $i }} نجوم ⭐</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-12">
                        @include('dashboard.partials.map_picker', [
                            'mapId' => 'edit_prop_map',
                            'lat' => old('latitude', $property->latitude),
                            'lng' => old('longitude', $property->longitude),
                            'latInputId' => 'latitude',
                            'lngInputId' => 'longitude',
                            'addressArId' => 'address_ar',
                            'addressEnId' => 'address_en',
                        ])
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العنوان (بالعربي)</label>
                        <input type="text" name="address_ar" id="address_ar" class="form-control" value="{{ old('address_ar', $property->address_ar) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العنوان (بالإنجليزي)</label>
                        <input type="text" name="address_en" id="address_en" class="form-control" value="{{ old('address_en', $property->address_en) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شروط وقواعد البيت (بالعربي)</label>
                        <textarea name="rules_ar" class="form-control" rows="2">{{ old('rules_ar', $property->rules_ar) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شروط وقواعد البيت (بالإنجليزي)</label>
                        <textarea name="rules_en" class="form-control" rows="2">{{ old('rules_en', $property->rules_en) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">حالة العقار *</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $property->status) == 'active' ? 'selected' : '' }}>نشط (Active)</option>
                            <option value="inactive" {{ old('status', $property->status) == 'inactive' ? 'selected' : '' }}>معطل (Inactive)</option>
                            <option value="draft" {{ old('status', $property->status) == 'draft' ? 'selected' : '' }}>مسودة (Draft)</option>
                            <option value="pending" {{ old('status', $property->status) == 'pending' ? 'selected' : '' }}>قيد الموافقة (Pending)</option>
                        </select>
                    </div>
                </div>

                <!-- Media Management (Logo & Gallery Images) -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-photo me-1"></i>الشعار والمعرض وصور العقار</h6>
                    </div>

                    <!-- Logo -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7 d-block">شعار العقار (Logo)</label>
                        @if($property->logo_url)
                            <div class="mb-2 position-relative d-inline-block">
                                <img src="{{ $property->logo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($property->title_ar) }}&background=6c757d&color=fff';" class="rounded border p-1" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>

                    <!-- Existing Gallery Images with individual deletion -->
                    <div class="col-md-8">
                        <label class="form-label fw-semibold fs-7 d-block">صور معرض العقار الحالية (انقر ❌ للحذف)</label>
                        <div class="d-flex flex-wrap gap-2 mb-2" id="galleryContainer">
                            @if(!empty($property->images) && is_array($property->images))
                                @foreach($property->images as $idx => $rawImg)
                                    @php $imgUrl = $property->gallery_urls[$idx] ?? asset($rawImg); @endphp
                                    <div class="position-relative border rounded p-1 bg-light text-center" id="imgBox_{{ $idx }}" style="width: 90px;">
                                        <img src="{{ $imgUrl }}" onerror="this.onerror=null; this.src='https://placehold.co/100x75/f8f9fa/6c757d?text=No+Image';" class="rounded w-100" style="height: 65px; object-fit: cover;">
                                        <input type="hidden" name="existing_images[]" value="{{ $rawImg }}" id="inputImg_{{ $idx }}">
                                        <button type="button" class="btn btn-sm btn-danger py-0 px-1 position-absolute top-0 end-0 m-1 rounded-circle" onclick="document.getElementById('imgBox_{{ $idx }}').remove();">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted fs-7">لا توجد صور معرض حالية.</span>
                            @endif
                        </div>
                        <label class="form-label fw-semibold fs-7 mt-2">إضافة صور جديدة للمعرض</label>
                        <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Custom Property Settings -->
                @php $hasCustom = !empty($property->settings); @endphp
                <div class="card bg-light-subtle border p-3 mb-4">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="has_custom_settings" value="1" id="hasCustomSettings" {{ $hasCustom ? 'checked' : '' }} onchange="document.getElementById('customSettingsDiv').style.display = this.checked ? 'block' : 'none';">
                        <label class="form-check-label fw-bold" for="hasCustomSettings">تخصيص سياسات وإعدادات خاصة بهذا العقار (Override Org Defaults)</label>
                    </div>

                    <div id="customSettingsDiv" style="{{ $hasCustom ? '' : 'display: none;' }}" class="pt-3 border-top mt-2">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-semibold">وقت تسجيل الدخول (Check-In Time)</label>
                                <input type="time" name="check_in_time" class="form-control" value="{{ $property->settings?->check_in_time ?? '14:00' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-semibold">وقت تسجيل المغادرة (Check-Out Time)</label>
                                <input type="time" name="check_out_time" class="form-control" value="{{ $property->settings?->check_out_time ?? '11:00' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-semibold">سياسة الإلغاء (بالعربي)</label>
                                <input type="text" name="cancellation_policy_ar" class="form-control" value="{{ $property->settings?->cancellation_policy ?? 'مرنة - إلغاء مجاني حتى 24 ساعة' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-7 fw-semibold">سياسة الإلغاء (بالإنجليزي)</label>
                                <input type="text" name="cancellation_policy_en" class="form-control" value="{{ $property->settings?->cancellation_policy ?? 'Flexible - Free cancellation up to 24h' }}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="allow_instant_booking" value="1" id="instantBooking" {{ ($property->settings?->allow_instant_booking ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fs-7 fw-semibold" for="instantBooking">السماح بالحجز الفوري المباشر (Instant Booking)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="requires_id_verification" value="1" id="idVerify" {{ ($property->settings?->requires_id_verification ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fs-7 fw-semibold" for="idVerify">يتطلب التحقق من الهوية الشخصية</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.orgs.show', $property->org_id) }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary-custom px-4">حفظ تحديثات العقار والصور</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editCountrySelect = document.getElementById('editCountrySelect');
        const editCitySelect = document.getElementById('editCitySelect');
        const editNeighSelect = document.getElementById('editNeighborhoodSelect');

        if (editCitySelect) {
            editCitySelect.addEventListener('change', function() {
                const cityId = this.value;
                const selectedOpt = this.options[this.selectedIndex];
                const countryId = selectedOpt ? selectedOpt.getAttribute('data-country-id') : null;

                if (countryId && editCountrySelect) {
                    editCountrySelect.value = countryId;
                }

                if (editNeighSelect) {
                    editNeighSelect.innerHTML = '<option value="">اختر الحي...</option>';
                    if (!cityId) return;

                    fetch(`/api/v1/neighborhoods?city_id=${cityId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (Array.isArray(data)) {
                                data.forEach(item => {
                                    const opt = document.createElement('option');
                                    opt.value = item.id;
                                    opt.textContent = item.name_ar || item.name_en;
                                    editNeighSelect.appendChild(opt);
                                });
                            }
                        }).catch(err => console.error("Error loading neighborhoods:", err));
                }
            });
        }
    });
</script>
@endpush
