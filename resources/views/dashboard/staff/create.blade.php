@extends('dashboard.layout')

@section('title', 'إضافة موظف وتعيين الصلاحيات - ' . $org->name_ar)
@section('page-title', 'إضافة موظف للمنظمة')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-1"><i class="ti ti-user-plus me-2 text-primary"></i>إضافة حساب موظف للمنظمة: {{ $org->name_ar }}</h5>
            <p class="text-muted fs-7 mb-4">إنشاء حساب جديد وتخصيص الصلاحيات المتاحة له بحسب دوره</p>
            <hr class="text-muted opacity-25 mb-4">

            <form action="{{ route('dashboard.staff.store', $org->id) }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم الموظف *</label>
                        <input type="text" name="name" class="form-control" required placeholder="محمد علي">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">البريد الإلكتروني لدخول اللوحة *</label>
                        <input type="email" name="email" class="form-control" required placeholder="staff@hotel.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" placeholder="+967 770 000 000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">كلمة المرور *</label>
                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="••••••••">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">الدور الوظيفي *</label>
                        <select name="role" class="form-select" required>
                            <option value="manager">مدير فرع / منشأة (Manager)</option>
                            <option value="receptionist" selected>موظف استقبال (Receptionist)</option>
                        </select>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 mt-4"><i class="ti ti-key me-2 text-warning"></i>مصفوفة الصلاحيات الخاصة بالحساب</h5>
                <p class="text-muted fs-7 mb-3">حدد الصلاحيات المسندة لهذا الحساب بدقة:</p>

                <div class="row g-3 mb-4 border rounded-3 p-3 bg-light-subtle">
                    @foreach($permissions as $permKey => $permTitle)
                        <div class="col-md-6">
                            <div class="form-check p-2 bg-white rounded border">
                                <input class="form-check-input ms-2" type="checkbox" name="permissions[{{ $permKey }}]" value="1" id="perm_{{ $permKey }}"
                                    {{ in_array($permKey, ['view_bookings', 'confirm_checkin', 'confirm_checkout']) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold fs-7 cursor-pointer" for="perm_{{ $permKey }}">
                                    {{ $permTitle }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.orgs.show', $org->id) }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary-custom px-4">إنشاء الحساب وتعيين الصلاحيات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
