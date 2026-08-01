@extends('dashboard.layout')

@section('title', 'سجل النشاطات والتغييرات - نظام التأجير')
@section('page-title', 'سجل النشاطات والتغيرات (Audit Log)')

@section('content')

<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="ti ti-history me-2 text-primary"></i>سجل كافة العمليات المنفذة في النظام</h5>
        <span class="badge bg-secondary-subtle text-dark fs-7">سجل غير قابل للتعديل</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light fs-7">
                <tr>
                    <th>الوقت والتاريخ</th>
                    <th>المستخدم / المنفذ</th>
                    <th>الدور</th>
                    <th>العملية (Action)</th>
                    <th>الكيان المـتأثر</th>
                    <th>عنوان IP</th>
                </tr>
            </thead>
            <tbody class="fs-7">
                @forelse($logs as $log)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '' }}</td>
                        <td class="fw-bold">{{ $log->user_name ?? 'نظام' }}</td>
                        <td><span class="badge bg-info-subtle text-info">{{ $log->user_role }}</span></td>
                        <td><code class="text-primary">{{ $log->action }}</code></td>
                        <td>
                            @if($log->subject_type)
                                <span class="badge bg-light text-dark">{{ $log->subject_type }} #{{ $log->subject_id }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-muted fs-7">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">لا توجد سجلات نشاط مسجلة حتى الآن.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>

@endsection
