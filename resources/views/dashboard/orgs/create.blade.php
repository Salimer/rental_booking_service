@extends('dashboard.layout')

@section('title', 'إضافة منظمة جديدة - نظام التأجير')
@section('page-title', 'إضافة منظمة جديدة')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.css">
    <style>
        .iti { width: 100%; }
        .iti__country-list {
            text-align: right;
            color: #212529 !important;
            background-color: #ffffff !important;
            z-index: 99999 !important;
            max-height: 220px !important;
            overflow-y: auto !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
        }
        .iti--container {
            z-index: 99999 !important;
        }
        .iti__country {
            padding: 8px 10px !important;
            color: #212529 !important;
        }
        .iti__country:hover, .iti__country.iti__highlight {
            background-color: #f8f9fa !important;
        }
        .iti--separate-dial-code .iti__selected-dial-code {
            font-family: sans-serif;
            direction: ltr;
        }
    </style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="ti ti-building-store me-2 text-primary"></i>بيانات المنظمة والمزود</h5>
            <hr class="text-muted opacity-25 mb-4">

            <form action="{{ route('dashboard.orgs.store') }}" method="POST" enctype="multipart/form-data" id="createOrgForm">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم المنظمة (بالعربي) *</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="مثال: فندق رويال بيتش">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم المنظمة (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Royal Beach Hotel">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شعار المنظمة (Logo)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">صورة الغلاف (Cover Photo)</label>
                        <input type="file" name="cover_photo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">رقم هاتف المنظمة</label>
                        <div class="w-100">
                            <input type="tel" id="contact_phone" name="contact_phone" class="form-control text-start" dir="ltr" style="direction: ltr; text-align: left;" placeholder="+967 770 000 000" value="{{ old('contact_phone') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">البريد الإلكتروني للتواصل والإشعارات</label>
                        <input type="email" name="contact_email" class="form-control" placeholder="info@hotel.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">المدينة</label>
                        <select name="city" class="form-select">
                            <option value="">اختر المدينة...</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->name_ar }}">{{ $c->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">نسبة عمولة المنصة (%)</label>
                        <input type="number" step="0.1" name="commission" class="form-control" value="10.0" min="0" max="100">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">العنوان بالتفصيل</label>
                        <input type="text" name="address_ar" class="form-control" placeholder="شارع التسعين - صنعاء">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">حالة المنظمة</label>
                        <select name="status" class="form-select">
                            <option value="active">نشطة (مفعلة مباشرة)</option>
                            <option value="pending">قيد المراجعه</option>
                            <option value="inactive">معطلة</option>
                        </select>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 mt-4"><i class="ti ti-user-plus me-2 text-success"></i>حساب مالك المنظمة (Vendor User)</h5>
                <p class="text-muted fs-7 mb-3">سيتم إنشاء حساب جديد لمالك هذه المنظمة للوصول المباشر للوحة التحكم</p>
                <hr class="text-muted opacity-25 mb-4">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم المالك / المزود *</label>
                        <input type="text" name="owner_name" class="form-control" required placeholder="أحمد سعيد">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">البريد الإلكتروني للدخول *</label>
                        <input type="email" name="owner_email" class="form-control" required placeholder="owner@hotel.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">كلمة المرور الحساب *</label>
                        <input type="password" name="owner_password" class="form-control" required minlength="6" placeholder="••••••••">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.orgs.list') }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary-custom px-4">حفظ وإضافة المنظمة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.querySelector("#contact_phone");
            if (phoneInput) {
                var iti = window.intlTelInput(phoneInput, {
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/utils.js",
                    autoHideDialCode: true,
                    autoPlaceholder: "ON",
                    formatOnDisplay: true,
                    initialCountry: "ye",
                    preferredCountries: ["ye", "sa", "ae"],
                    placeholderNumberType: "MOBILE",
                    separateDialCode: true
                });

                var form = document.querySelector("#createOrgForm");
                if (form) {
                    form.addEventListener("submit", function(e) {
                        var val = phoneInput.value.trim();
                        if (val !== "") {
                            if (!iti.isValidNumber()) {
                                alert("يرجى إدخال رقم هاتف صحيح شامل رمز الدولة.");
                                e.preventDefault();
                                return false;
                            }
                            phoneInput.value = iti.getNumber();
                        }
                    });
                }
            }
        });
    </script>
@endpush
