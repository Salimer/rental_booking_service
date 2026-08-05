@extends('dashboard.layout')

@php
    $isCreating = $isCreating ?? false;
    $backUrl = ($unit->property && $unit->property->org_id)
        ? route('dashboard.orgs.show', $unit->property->org_id)
        : route('dashboard.orgs.list');
@endphp

@section('title', $isCreating ? 'إضافة وحدة إيواء جديدة' : 'تعديل وحدة الإيواء - ' . $unit->name_ar)
@section('page-title', $isCreating ? 'إضافة بيانات والأسعار والصور للوحدة' : 'تعديل بيانات والأسعار والصور للوحدة')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    <i class="ti {{ $isCreating ? 'ti-plus' : 'ti-door-enter' }} me-2 text-primary"></i>
                    {{ $isCreating ? 'إضافة وحدة إيواء جديدة' : 'تعديل وحدة الإيواء: ' . $unit->name_ar }}
                </h5>
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> العودة للمنظمة والعقار
                </a>
            </div>

            <form action="{{ $isCreating ? route('dashboard.units.store') : route('dashboard.units.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Info -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-info-circle me-1"></i>بيانات الوحدة والعقار</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العقار التابع له *</label>
                        <select name="property_id" class="form-select" required>
                            @foreach($properties as $p)
                                <option value="{{ $p->id }}" {{ old('property_id', $unit->property_id) == $p->id ? 'selected' : '' }}>{{ $p->title_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">طريقة التسعير *</label>
                        <select name="pricing_mode" class="form-select" required>
                            <option value="per_night" {{ old('pricing_mode', $unit->pricing_mode ?? 'per_night') == 'per_night' ? 'selected' : '' }}>بالليلة (Per Night)</option>
                            <option value="per_hour" {{ old('pricing_mode', $unit->pricing_mode) == 'per_hour' ? 'selected' : '' }}>بالساعة (Per Hour)</option>
                            <option value="per_slot" {{ old('pricing_mode', $unit->pricing_mode) == 'per_slot' ? 'selected' : '' }}>بالفترة (Per Slot)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم الوحدة (بالعربي) *</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $unit->name_ar) }}" required placeholder="جناح ملكي - رقم 101">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم الوحدة (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $unit->name_en) }}" placeholder="Royal Suite #101">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">وصف الوحدة (بالعربي)</label>
                        <textarea name="description_ar" class="form-control" rows="2">{{ old('description_ar', $unit->description_ar) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">وصف الوحدة (بالإنجليزي)</label>
                        <textarea name="description_en" class="form-control" rows="2">{{ old('description_en', $unit->description_en) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">أقصى عدد ضيوف *</label>
                        <input type="number" name="max_guests" class="form-control" value="{{ old('max_guests', $unit->max_guests ?? 2) }}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">عدد الوحدات المتاحة (Quantity) *</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $unit->quantity ?? 1) }}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">حالة الوحدة *</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $unit->status ?? 'active') == 'active' ? 'selected' : '' }}>نشطة (Active)</option>
                            <option value="inactive" {{ old('status', $unit->status) == 'inactive' ? 'selected' : '' }}>معطلة (Inactive)</option>
                        </select>
                    </div>
                </div>

                <!-- 4 Supported Currencies Prices -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-report-money me-1"></i>أسعار العملات الأربع المدعومة</h6>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">ريال سعودي (SAR) *</label>
                        <input type="number" step="0.01" name="price_sar" class="form-control" value="{{ old('price_sar', $defaultPrice?->price_sar ?? 0) }}" required placeholder="250.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">ريال يمني (شمال) YER</label>
                        <input type="number" step="0.01" name="price_yer_n" class="form-control" value="{{ old('price_yer_n', $defaultPrice?->price_yer_n ?? 0) }}" placeholder="35000.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">ريال يمني (جنوب) YER</label>
                        <input type="number" step="0.01" name="price_yer_s" class="form-control" value="{{ old('price_yer_s', $defaultPrice?->price_yer_s ?? 0) }}" placeholder="110000.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">دولار أمريكي (USD)</label>
                        <input type="number" step="0.01" name="price_usd" class="form-control" value="{{ old('price_usd', $defaultPrice?->price_usd ?? 0) }}" placeholder="65.00">
                    </div>
                </div>

                <!-- Amenities with Quantity -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-star me-1"></i>المرافق والخدمات المتاحة للوحدة (مع تحديد الكميات)</h6>
                    </div>
                    <div class="col-12">
                        <div class="row g-2">
                            @php
                                $attachedAmenities = ($unit->exists && $unit->relationLoaded('amenities')) ? $unit->amenities->keyBy('id') : collect();
                            @endphp
                            @foreach($allAmenities as $am)
                                @php
                                    $isAttached = $attachedAmenities->has($am->id);
                                    $pivotQty = $isAttached ? ($attachedAmenities[$am->id]->pivot->quantity ?? 1) : 1;
                                @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="border rounded p-2 d-flex align-items-center justify-content-between bg-light-subtle">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="amenity_ids[]" value="{{ $am->id }}" id="uAm_{{ $am->id }}" {{ $isAttached ? 'checked' : '' }}>
                                            <label class="form-check-label fs-7 fw-semibold" for="uAm_{{ $am->id }}">{{ $am->name_ar }}</label>
                                        </div>
                                        <input type="number" name="amenity_quantities[{{ $am->id }}]" class="form-control form-control-sm ms-2" value="{{ $pivotQty }}" min="1" style="width: 65px;" placeholder="العدد">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Unit Images Management -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3"><i class="ti ti-photo me-1"></i>صور الوحدة {{ $isCreating ? '' : '(انقر ❌ للحذف)' }}</h6>
                    </div>
                    <div class="col-12">
                        @if(!$isCreating)
                            <div class="d-flex flex-wrap gap-2 mb-3" id="unitImagesContainer">
                                @if(!empty($unit->images) && is_array($unit->images))
                                    @foreach($unit->images as $idx => $rawImg)
                                        @php $imgUrl = $unit->image_urls[$idx] ?? asset($rawImg); @endphp
                                        <div class="position-relative border rounded p-1 bg-light text-center" id="uImgBox_{{ $idx }}" style="width: 100px;">
                                            <img src="{{ $imgUrl }}" onerror="this.onerror=null; this.src='https://placehold.co/100x75/f8f9fa/6c757d?text=No+Image';" class="rounded w-100" style="height: 70px; object-fit: cover;">
                                            <input type="hidden" name="existing_images[]" value="{{ $rawImg }}" id="uInputImg_{{ $idx }}">
                                            <button type="button" class="btn btn-sm btn-danger py-0 px-1 position-absolute top-0 end-0 m-1 rounded-circle" onclick="document.getElementById('uImgBox_{{ $idx }}').remove();">
                                                &times;
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted fs-7">لا توجد صور حالية لهذه الوحدة.</span>
                                @endif
                            </div>
                        @endif
                        <label class="form-label fw-semibold fs-7">{{ $isCreating ? 'رفع صور الوحدة' : 'إضافة صور جديدة للوحدة' }}</label>
                        <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ $backUrl }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary-custom px-4">{{ $isCreating ? 'حفظ وإضافة الوحدة' : 'حفظ تحديثات الوحدة والأسعار والصور' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
