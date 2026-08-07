@extends('dashboard.layout')

@section('title', 'تفاصيل المنظمة - ' . $org->name_ar)
@section('page-title', 'العرض الشامل للمنظمة')

@section('content')

<!-- Org Top Banner Card -->
<div class="card-custom p-4 mb-4 overflow-hidden position-relative" style="{{ $org->cover_photo_url ? 'background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url(' . $org->cover_photo_url . ') center/cover;' : '' }}">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 position-relative style-z-1 {{ $org->cover_photo_url ? 'text-white' : '' }}">
        <div class="d-flex align-items-center gap-3">
            @if($org->logo_url)
                <img src="{{ $org->logo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($org->name_ar) }}&background=0d6efd&color=fff';" class="rounded-3 border border-2 border-white shadow-sm" style="width: 65px; height: 65px; object-fit: cover; background: #fff;">
            @else
                <div style="width: 65px; height: 65px; border-radius: 12px;" class="org-avatar-icon d-flex align-items-center justify-content-center fw-bold fs-3 bg-primary-subtle text-primary">
                    <i class="ti ti-building-store"></i>
                </div>
            @endif
            <div>
                <h4 class="fw-bold mb-1 {{ $org->cover_photo_url ? 'text-white' : 'text-dark' }}">{{ $org->name_ar }}</h4>
                <div class="d-flex flex-wrap gap-2 fs-7 {{ $org->cover_photo_url ? 'text-white-50' : 'text-muted' }}">
                    <span>كود: <strong class="{{ $org->cover_photo_url ? 'text-white' : '' }}">{{ $org->code }}</strong></span>
                    <span>•</span>
                    <span>المدينة: <strong class="{{ $org->cover_photo_url ? 'text-white' : '' }}">{{ $org->city ?? 'غير محددة' }}</strong></span>
                    <span>•</span>
                    <span>نسبة العمولة: <strong class="text-success">{{ number_format($org->commission, 1) }}%</strong></span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            @if($user->isAdmin())
                <a href="{{ route('dashboard.staff.create', $org->id) }}" class="btn btn-outline-success">
                    <i class="ti ti-user-plus me-1"></i> إضافة موظف للمنظمة
                </a>
            @endif

            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addPropertyModal">
                <i class="ti ti-plus me-1"></i> إضافة عقار جديد
            </button>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs nav-tabs-custom border-bottom mb-4" id="orgTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold px-4 py-3" id="properties-tab" data-bs-toggle="tab" data-bs-target="#properties-content" type="button">
            <i class="ti ti-home me-1"></i> العقارات والوحدات ({{ $org->properties->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold px-4 py-3" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings-content" type="button">
            <i class="ti ti-receipt me-1"></i> الحجوزات ({{ $bookings->total() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold px-4 py-3" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff-content" type="button">
            <i class="ti ti-users me-1"></i> الموظفون والطاقم ({{ $staff->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold px-4 py-3" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-content" type="button">
            <i class="ti ti-info-circle me-1"></i> بيانات المنظمة
        </button>
    </li>
</ul>

<!-- Tab Contents -->
<div class="tab-content" id="orgTabsContent">

    <!-- Tab 1: Properties & Units -->
    <div class="tab-pane fade show active" id="properties-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">قائمة العقارات والوحدات التابعة</h5>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($org->properties as $prop)
                <div class="card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @if($prop->logo_url)
                                <img src="{{ $prop->logo_url }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($prop->title_ar) }}&background=6c757d&color=fff';" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <div class="bg-primary-subtle text-primary p-2 rounded-3 fs-4">
                                    <i class="ti ti-home-2"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="fw-bold mb-0">{{ $prop->title_ar }}</h5>
                                <div class="text-muted fs-7">
                                    <span>النوع: {{ $prop->type->name_ar ?? 'غير محدد' }}</span> |
                                    <span>العنوان: {{ $prop->address_ar ?? 'غير محدد' }}</span> |
                                    <span>الحالة: <span class="badge bg-secondary-subtle text-dark">{{ $prop->status }}</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard.properties.edit', $prop->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-edit me-1"></i> تعديل العقار
                            </a>
                            <a href="{{ route('dashboard.units.create', ['property_id' => $prop->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-plus me-1"></i> إضافة وحدة إيواء
                            </a>
                            <form action="{{ route('dashboard.properties.delete', $prop->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت تأكد من رغبتك في حذف هذا العقار وجميع وحداته؟');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Inline Units List -->
                    <div class="border rounded-3 p-3 bg-light-subtle">
                        <h6 class="fw-bold mb-3 text-muted fs-7"><i class="ti ti-door me-1"></i>وحدات الإيواء داخل هذا العقار:</h6>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 bg-white rounded-2 overflow-hidden shadow-sm">
                                <thead class="table-light fs-7">
                                    <tr>
                                        <th>الصورة</th>
                                        <th>اسم الوحدة</th>
                                        <th>التسعير / ضيوف / كمية</th>
                                        <th>أسعار العملات (لليلة)</th>
                                        <th>الحالة</th>
                                        <th class="text-end">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-7">
                                    @forelse($prop->units as $u)
                                        @php
                                            $defaultP = $u->prices->firstWhere('price_type', 'default');
                                            $sarPrice = $defaultP ? $defaultP->price_sar : 0;
                                            $yerNPrice = $defaultP ? $defaultP->price_yer_n : 0;
                                            $yerSPrice = $defaultP ? $defaultP->price_yer_s : 0;
                                            $usdPrice = $defaultP ? $defaultP->price_usd : 0;
                                            $firstImage = (!empty($u->image_urls) && is_array($u->image_urls)) ? $u->image_urls[0] : null;
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($firstImage)
                                                    <img src="{{ $firstImage }}" onerror="this.onerror=null; this.src='https://placehold.co/100x75/f8f9fa/6c757d?text=No+Image';" class="rounded" style="width: 48px; height: 36px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 36px;">
                                                        <i class="ti ti-photo"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="fw-bold">{{ $u->name_ar ?? $u->name }}</td>
                                            <td>{{ $u->pricing_mode }} | {{ $u->max_guests }} ضيوف | {{ $u->quantity }} وحدات</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1 fs-7">
                                                    <span class="badge bg-success-subtle text-success">SAR: {{ number_format($sarPrice, 2) }}</span>
                                                    @if($yerNPrice > 0)
                                                        <span class="badge bg-info-subtle text-info">YER (شمال): {{ number_format($yerNPrice) }}</span>
                                                    @endif
                                                    @if($yerSPrice > 0)
                                                        <span class="badge bg-warning-subtle text-warning">YER (جنوب): {{ number_format($yerSPrice) }}</span>
                                                    @endif
                                                    @if($usdPrice > 0)
                                                        <span class="badge bg-primary-subtle text-primary">USD: ${{ number_format($usdPrice, 2) }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">{{ $u->status }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('dashboard.units.calendar', $u->id) }}" class="btn btn-sm btn-outline-dark" title="تقويم الإتاحة">
                                                        <i class="ti ti-calendar"></i>
                                                    </a>
                                                    <a href="{{ route('dashboard.units.edit', $u->id) }}" class="btn btn-sm btn-outline-secondary" title="تعديل الوحدة والصور">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <form action="{{ route('dashboard.units.delete', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت تأكد من حذف هذه الوحدة؟');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف الوحدة">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-3 text-muted">لا توجد وحدات مضافة بعد في هذا العقار.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card-custom p-5 text-center text-muted">
                    <i class="ti ti-home-off fs-1 d-block mb-2"></i>
                    لا توجد عقارات مضافة لهذه المنظمة حتى الآن.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tab 2: Bookings -->
    <div class="tab-pane fade" id="bookings-content">
        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3">سجل حجوزات المنظمة</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>رقم الحجز</th>
                            <th>العقار والوحدة</th>
                            <th>العميل</th>
                            <th>تاريخ الوصول</th>
                            <th>المبلغ الإجمالي</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($bookings as $b)
                            <tr>
                                <td class="fw-bold">#{{ $b->booking_code ?? $b->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $b->property->title_ar ?? '' }}</div>
                                    <small class="text-muted">{{ $b->unit->name_ar ?? '' }}</small>
                                </td>
                                <td>{{ $b->guest_name ?? $b->user->name ?? 'زائر' }}</td>
                                <td>{{ $b->check_in_date }}</td>
                                <td class="fw-bold text-success">{{ number_format($b->total_price, 2) }} {{ $b->currency ?? 'SAR' }}</td>
                                <td><span class="badge bg-info-subtle text-info">{{ $b->status }}</span></td>
                                <td>
                                    <a href="{{ route('dashboard.bookings.show', $b->id) }}" class="btn btn-sm btn-light">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد حجوزات.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $bookings->links() }}</div>
        </div>
    </div>

    <!-- Tab 3: Staff -->
    <div class="tab-pane fade" id="staff-content">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">طاقم وموظفو المنظمة</h5>
                @if($user->isAdmin())
                    <a href="{{ route('dashboard.staff.create', $org->id) }}" class="btn btn-primary-custom btn-sm">
                        <i class="ti ti-plus me-1"></i> إضافة موظف وتعيين الصلاحيات
                    </a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light fs-7">
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>تاريخ آخر دخول</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="fs-7">
                        @forelse($staff as $s)
                            <tr>
                                <td class="fw-bold">{{ $s->name }}</td>
                                <td>{{ $s->email }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark">
                                        {{ $s->isOwner() ? 'مالك المنظمة' : ($s->isManager() ? 'مدير' : 'موظف استقبال') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $s->status ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        {{ $s->status ? 'مفعل' : 'معطل' }}
                                    </span>
                                </td>
                                <td>{{ $s->last_login_at ? $s->last_login_at->diffForHumans() : 'لم يدخل بعد' }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <div class="d-flex gap-1 align-items-center">
                                            @if($s->id !== $user->id)
                                                <form action="{{ route('dashboard.staff.impersonate', $s->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="تصفح النظام بحساب هذا الموظف">
                                                        <i class="ti ti-user-check me-1"></i> دخول كـ
                                                    </button>
                                                </form>
                                            @endif

                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $s->id }}" title="تغيير كلمة المرور">
                                                <i class="ti ti-key me-1"></i> كلمة المرور
                                            </button>

                                            @if(!$s->isOwner())
                                                <form action="{{ route('dashboard.staff.toggle-status', $s->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        {{ $s->status ? 'تعطيل' : 'تفعيل' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Reset Password Modal -->
                                        <div class="modal fade text-start" id="resetPasswordModal{{ $s->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('dashboard.staff.reset-password', $s->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">إعادة تعيين كلمة المرور - {{ $s->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-muted fs-7 mb-3">سيتم تعيين كلمة مرور جديدة للمستخدم دون الحاجة لمعرفة كلمة المرور القديمة.</p>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold fs-7">كلمة المرور الجديدة *</label>
                                                                <input type="password" name="password" class="form-control" required minlength="6" placeholder="******">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold fs-7">تأكيد كلمة المرور الجديدة *</label>
                                                                <input type="password" name="password_confirmation" class="form-control" required minlength="6" placeholder="******">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-primary-custom">حفظ كلمة المرور الجديدة</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">لا يوجد موظفون مضافون لهذه المنظمة بعد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 4: Info & Settings -->
    <div class="tab-pane fade" id="info-content">
        @if($org->dashboardUser)
            <div class="card border border-primary-subtle bg-primary-subtle p-3 rounded-3 mb-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white p-3 rounded-circle fs-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ti ti-user-star"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">حساب مالك المنظمة (Owner Account)</h6>
                            <div class="fs-7 text-muted">
                                <span class="me-3">الاسم: <strong class="text-dark">{{ $org->dashboardUser->name }}</strong></span>
                                <span class="me-3">البريد: <strong class="text-dark">{{ $org->dashboardUser->email }}</strong></span>
                                <span>الهاتف: <strong class="text-dark">{{ $org->dashboardUser->phone ?? 'غير محدد' }}</strong></span>
                            </div>
                        </div>
                    </div>
                    @if($user->isAdmin())
                        <div class="d-flex gap-2">
                            <form action="{{ route('dashboard.staff.impersonate', $org->dashboardUser->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary fw-semibold btn-sm">
                                    <i class="ti ti-user-check me-1"></i> تصفح كـ مالك المنظمة
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-dark fw-semibold btn-sm" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $org->dashboardUser->id }}">
                                <i class="ti ti-key me-1"></i> إعادة تعيين كلمة المرور
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="card-custom p-4">
            <h5 class="fw-bold mb-3">تعديل بيانات المنظمة</h5>
            <form action="{{ route('dashboard.orgs.update', $org->id) }}" method="POST" enctype="multipart/form-data" id="editOrgForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم المنظمة (بالعربي)</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ $org->name_ar }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم المنظمة (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ $org->name_en }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شعار المنظمة (Logo)</label>
                        @if($org->logo)
                            <div class="mb-2">
                                <img src="{{ asset($org->logo) }}" class="rounded border" style="height: 50px; object-fit: contain;">
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">صورة الغلاف (Cover Photo)</label>
                        @if($org->cover_photo)
                            <div class="mb-2">
                                <img src="{{ asset($org->cover_photo) }}" class="rounded border" style="height: 50px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="cover_photo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">هاتف المنظمة</label>
                        <div class="w-100">
                            <input type="tel" id="contact_phone" name="contact_phone" class="form-control text-start" dir="ltr" style="direction: ltr; text-align: left;" value="{{ old('contact_phone', $org->contact_phone) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">البريد الإلكتروني</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $org->contact_email }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">المدينة</label>
                        <input type="text" name="city" class="form-control" value="{{ $org->city }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العنوان</label>
                        <input type="text" name="address_ar" class="form-control" value="{{ $org->address_ar }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العملة المفضلة (Preferred Currency)</label>
                        <select name="preferred_currency" class="form-select">
                            <option value="YER_N" {{ old('preferred_currency', $org->preferred_currency) == 'YER_N' ? 'selected' : '' }}>ريال يمني (شمال - YER_N)</option>
                            <option value="YER_S" {{ old('preferred_currency', $org->preferred_currency) == 'YER_S' ? 'selected' : '' }}>ريال يمني (جنوب - YER_S)</option>
                            <option value="SAR" {{ old('preferred_currency', $org->preferred_currency) == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                            <option value="USD" {{ old('preferred_currency', $org->preferred_currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        </select>
                    </div>
                    @if($user->isAdmin())
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7">نسبة العمولة (%)</label>
                            <input type="number" step="0.1" name="commission" class="form-control" value="{{ $org->commission }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $org->status == 'active' ? 'selected' : '' }}>نشطة</option>
                                <option value="inactive" {{ $org->status == 'inactive' ? 'selected' : '' }}>معطلة</option>
                                <option value="pending" {{ $org->status == 'pending' ? 'selected' : '' }}>قيد الموافقة</option>
                            </select>
                        </div>
                    @endif
                </div>

                <!-- Free Night Promotion (Book 3 Pay 2) Settings -->
                <div class="card bg-light-subtle border p-3 mt-4">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="free_night_enabled" value="1" id="freeNightEnabled" {{ ($org->settings?->free_night_enabled ?? false) ? 'checked' : '' }} onchange="document.getElementById('freeNightDiv').style.display = this.checked ? 'block' : 'none';">
                        <label class="form-check-label fw-bold" for="freeNightEnabled">
                            <i class="ti ti-gift text-primary me-1"></i>تفعيل عرض الليالي المجانية (مثال: احجز 3 ليالٍ وادفع 2)
                        </label>
                    </div>

                    <div id="freeNightDiv" style="{{ ($org->settings?->free_night_enabled ?? false) ? '' : 'display: none;' }}" class="pt-3 border-top mt-2">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fs-7 fw-semibold">الحد الأدنى لليالي للاستفادة *</label>
                                <input type="number" name="free_night_min_nights" class="form-control" value="{{ $org->settings?->free_night_min_nights ?? 3 }}" min="1">
                                <small class="text-muted fs-7">عدد الليالي المطلوبة لتفعيل الخصم (مثلاً: 3)</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-7 fw-semibold">الحد الأقصى لليالي (اختياري)</label>
                                <input type="number" name="free_night_max_nights" class="form-control" value="{{ $org->settings?->free_night_max_nights ?? '' }}" min="1" placeholder="بلا حد أقصى">
                                <small class="text-muted fs-7">اتركه فارغاً إذا كان ينطبق على أي إقامة أطول</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fs-7 fw-semibold">عدد الليالي المجانية *</label>
                                <input type="number" name="free_nights_count" class="form-control" value="{{ $org->settings?->free_nights_count ?? 1 }}" min="1">
                                <small class="text-muted fs-7">عدد الليالي التي سيتم خصم قيمتها (مثلاً: 1)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        @if($user->isAdmin())
                            <form action="{{ route('dashboard.orgs.delete', $org->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متاكد من نقل المنظمة للقمامة؟');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger me-2">
                                    <i class="ti ti-trash me-1"></i> نقل للقمامة
                                </button>
                            </form>
                            <form action="{{ route('dashboard.orgs.hard-delete', $org->id) }}" method="POST" class="d-inline" onsubmit="return confirm('تحذير: هذا إجراء نهائي وسيتم حذف المنظمة بجميع عقاراتها ووحداتها! هل تريد المتابعة؟');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="ti ti-alert-triangle me-1"></i> حذف نهائي شمل العقارات
                                </button>
                            </form>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary-custom px-4">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Modal: Add Property -->
<div class="modal fade" id="addPropertyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dashboard.properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="org_id" value="{{ $org->id }}">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="ti ti-building-hospital me-1 text-primary"></i> إضافة عقار جديد لـ {{ $org->name_ar }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <!-- Basic Info -->
                    <div class="col-12">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-1"><i class="ti ti-info-circle me-1"></i>البيانات الأساسية</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم العقار (بالعربي) *</label>
                        <input type="text" name="name_ar" class="form-control" required placeholder="برج الأمل الفندقي">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">اسم العقار (بالإنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" placeholder="Al Amal Hotel Tower">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">وصف العقار (بالعربي)</label>
                        <textarea name="description_ar" class="form-control" rows="2" placeholder="وصف تفصيلي للعقار باللغة العربية..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">وصف العقار (بالإنجليزي)</label>
                        <textarea name="description_en" class="form-control" rows="2" placeholder="Property detailed description in English..."></textarea>
                    </div>

                    <!-- Category & Attributes -->
                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-1"><i class="ti ti-map-pin me-1"></i>التصنيف والموقع والتصنيف النجمي</h6>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">تصنيف الإيواء *</label>
                        <select name="type_id" class="form-select" required>
                            <option value="">اختر التصنيف...</option>
                            @foreach($allTypes as $t)
                                <option value="{{ $t->id }}">{{ $t->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">الدولة</label>
                        <select name="country_id" id="modal_country_id" class="form-select">
                            <option value="">اختر الدولة...</option>
                            @foreach($allCountries as $co)
                                <option value="{{ $co->id }}" {{ $co->name_ar == 'اليمن' ? 'selected' : '' }}>{{ $co->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">المدينة</label>
                        <select name="city_id" id="modal_city_id" class="form-select">
                            <option value="">اختر المدينة...</option>
                            @foreach($allCities as $c)
                                <option value="{{ $c->id }}" data-country-id="{{ $c->country_id }}">{{ $c->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">الحي / المنطقة</label>
                        <select name="neighborhood_id" id="modal_neighborhood_id" class="form-select">
                            <option value="">اختر الحي...</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold fs-7">تقييم النجوم (Star Rating)</label>
                        <select name="star_rating" class="form-select">
                            <option value="">بدون تقييم</option>
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}">{{ $i }} نجوم ⭐</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Map Picker Component -->
                    <div class="col-12">
                        @include('dashboard.partials.map_picker', [
                            'mapId' => 'modal_prop_map',
                            'lat' => null,
                            'lng' => null,
                            'latInputId' => 'modal_latitude',
                            'lngInputId' => 'modal_longitude',
                            'addressArId' => 'modal_address_ar',
                            'addressEnId' => 'modal_address_en',
                        ])
                    </div>

                    <!-- Address & Rules -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العنوان (بالعربي)</label>
                        <input type="text" name="address_ar" id="modal_address_ar" class="form-control" placeholder="شارع حدة - صنعاء">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">العنوان (بالإنجليزي)</label>
                        <input type="text" name="address_en" id="modal_address_en" class="form-control" placeholder="Hadda St, Sana'a">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شروط وقواعد البيت (بالعربي)</label>
                        <textarea name="rules_ar" class="form-control" rows="2" placeholder="شروط الإقامة وإرشادات البيت بالعربي..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شروط وقواعد البيت (بالإنجليزي)</label>
                        <textarea name="rules_en" class="form-control" rows="2" placeholder="House rules & accommodation policies in English..."></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold fs-7">حالة العقار *</label>
                        <select name="status" class="form-select" required>
                            <option value="active">نشط (Active)</option>
                            <option value="pending">قيد الموافقة (Pending)</option>
                            <option value="draft">مسودة (Draft)</option>
                            <option value="inactive">معطل (Inactive)</option>
                        </select>
                    </div>

                    <!-- Media Uploads -->
                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-1"><i class="ti ti-photo me-1"></i>الشعار وصور المعرض</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">شعار العقار (Logo)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">صور المعرض (Gallery)</label>
                        <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                    </div>

                    <!-- Custom Settings Override Switch -->
                    <div class="col-12 mt-3">
                        <div class="card bg-light-subtle border p-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="has_custom_settings" value="1" id="modalHasCustomSettings" onchange="document.getElementById('modalCustomSettingsDiv').style.display = this.checked ? 'block' : 'none';">
                                <label class="form-check-label fw-bold" for="modalHasCustomSettings">تخصيص سياسات وإعدادات خاصة بهذا العقار (Override Org Defaults)</label>
                            </div>

                            <div id="modalCustomSettingsDiv" style="display: none;" class="pt-3 border-top mt-2">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fs-7 fw-semibold">وقت تسجيل الدخول (Check-In Time)</label>
                                        <input type="time" name="check_in_time" class="form-control" value="14:00">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-7 fw-semibold">وقت تسجيل المغادرة (Check-Out Time)</label>
                                        <input type="time" name="check_out_time" class="form-control" value="11:00">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-7 fw-semibold">سياسة الإلغاء (بالعربي)</label>
                                        <input type="text" name="cancellation_policy_ar" class="form-control" value="مرنة - إلغاء مجاني حتى 24 ساعة">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-7 fw-semibold">سياسة الإلغاء (بالإنجليزي)</label>
                                        <input type="text" name="cancellation_policy_en" class="form-control" value="Flexible - Free cancellation up to 24h">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" name="allow_instant_booking" value="1" id="modalInstantBooking" checked>
                                            <label class="form-check-label fs-7 fw-semibold" for="modalInstantBooking">السماح بالحجز الفوري المباشر (Instant Booking)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" name="requires_id_verification" value="1" id="modalIdVerify">
                                            <label class="form-check-label fs-7 fw-semibold" for="modalIdVerify">يتطلب التحقق من الهوية الشخصية</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary-custom px-4">إضافة العقار</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

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

                var existingVal = "{{ old('contact_phone', $org->contact_phone ?? '') }}".trim();
                if (existingVal) {
                    if (!existingVal.startsWith('+') && !existingVal.startsWith('00')) {
                        if (existingVal.startsWith('967') || existingVal.startsWith('966')) {
                            existingVal = '+' + existingVal;
                        }
                    }
                    iti.setNumber(existingVal);
                }

                var form = document.querySelector("#editOrgForm");
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

            // Neighborhood & Country Sync for Add Property Modal
            const modalCountrySelect = document.getElementById('modal_country_id');
            const modalCitySelect = document.getElementById('modal_city_id');
            const modalNeighSelect = document.getElementById('modal_neighborhood_id');

            if (modalCitySelect) {
                modalCitySelect.addEventListener('change', function() {
                    const cityId = this.value;
                    const selectedOpt = this.options[this.selectedIndex];
                    const countryId = selectedOpt ? selectedOpt.getAttribute('data-country-id') : null;

                    if (countryId && modalCountrySelect) {
                        modalCountrySelect.value = countryId;
                    }

                    if (modalNeighSelect) {
                        modalNeighSelect.innerHTML = '<option value="">اختر الحي...</option>';
                        if (!cityId) return;

                        fetch(`/api/v1/neighborhoods?city_id=${cityId}`)
                            .then(res => res.json())
                            .then(data => {
                                if (Array.isArray(data)) {
                                    data.forEach(item => {
                                        const opt = document.createElement('option');
                                        opt.value = item.id;
                                        opt.textContent = item.name_ar || item.name_en;
                                        modalNeighSelect.appendChild(opt);
                                    });
                                }
                            }).catch(err => console.error("Error loading neighborhoods:", err));
                    }
                });
            }
        });
    </script>
@endpush
