@extends('test-dashboard.layout')

@section('title', __('dashboard.nav.bookings'))

@section('content')
<div class="space-y-6">
    
    <!-- Title -->
    <div>
        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
            <span>📅</span> {{ __('dashboard.nav.bookings') }}
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            سجل جميع عمليات الحجز، مستندات الدفع، التغييرات التاريخية للحالات، والتثبيت المؤقت للتواريخ.
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-gray-800 pb-3">
        <a href="{{ route('test-dashboard.bookings', ['tab' => 'bookings']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'bookings' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            📋 {{ __('dashboard.tabs.all_bookings') }} ({{ count($bookings) }})
        </a>
        <a href="{{ route('test-dashboard.bookings', ['tab' => 'payments']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'payments' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            💳 {{ __('dashboard.tabs.payments') }} ({{ count($payments) }})
        </a>
        <a href="{{ route('test-dashboard.bookings', ['tab' => 'status_logs']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'status_logs' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            📜 {{ __('dashboard.tabs.status_logs') }} ({{ count($statusLogs) }})
        </a>
        <a href="{{ route('test-dashboard.bookings', ['tab' => 'holds']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'holds' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            ⏳ {{ __('dashboard.tabs.holds') }} ({{ count($dateHolds) }})
        </a>
    </div>

    <!-- TAB 1: BOOKINGS -->
    @if($tab === 'bookings')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">رقم الحجز</th>
                    <th class="p-3">معرف العميل</th>
                    <th class="p-3">الوحدة / العقار</th>
                    <th class="p-3">تاريخ الوصول</th>
                    <th class="p-3">تاريخ المغادرة</th>
                    <th class="p-3">المبلغ الإجمالي</th>
                    <th class="p-3">الحالة</th>
                    <th class="p-3">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono font-bold text-indigo-400">#{{ $b->id }}</td>
                    <td class="p-3 font-mono text-gray-400">User #{{ $b->user_id }}</td>
                    <td class="p-3 font-bold text-white">{{ $b->unit->name ?? ($b->property->name ?? 'وحدة #'.$b->unit_id) }}</td>
                    <td class="p-3 font-mono text-emerald-300">{{ $b->check_in_date }}</td>
                    <td class="p-3 font-mono text-rose-300">{{ $b->check_out_date }}</td>
                    <td class="p-3 font-extrabold text-emerald-400">{{ number_format($b->unit_price * ($b->nights_count ?? 1), 2) }} {{ $b->currency }}</td>
                    <td class="p-3">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold 
                            {{ $b->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : '' }}
                            {{ $b->status === 'pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : '' }}
                            {{ $b->status === 'cancelled' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : '' }}">
                            {{ __('dashboard.status.' . strtolower($b->status)) ?? $b->status }}
                        </span>
                    </td>
                    <td class="p-3 text-gray-500 font-mono text-[11px]">{{ $b->created_at ? $b->created_at->format('Y-m-d H:i') : '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- TAB 2: PAYMENTS -->
    @if($tab === 'payments')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">معرف الحجز</th>
                    <th class="p-3">معرف الدفعة (Transaction ID)</th>
                    <th class="p-3">طريقة الدفع</th>
                    <th class="p-3">المبلغ</th>
                    <th class="p-3">حالة الدفع</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($payments as $pay)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $pay->id }}</td>
                    <td class="p-3 font-mono text-indigo-400">#{{ $pay->booking_id }}</td>
                    <td class="p-3 font-mono text-gray-300">{{ $pay->transaction_id ?? $pay->payment_reference ?? '—' }}</td>
                    <td class="p-3 text-purple-300 font-medium">{{ $pay->payment_method ?? 'بطاقة إلكترونية' }}</td>
                    <td class="p-3 font-bold text-emerald-400">{{ number_format($pay->amount, 2) }} {{ $pay->currency ?? 'SAR' }}</td>
                    <td class="p-3 font-bold text-emerald-300">{{ $pay->status ?? 'مكتمل' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- TAB 3: STATUS LOGS -->
    @if($tab === 'status_logs')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">رقم الحجز</th>
                    <th class="p-3">الحالة السابقة</th>
                    <th class="p-3">الحالة الجديدة</th>
                    <th class="p-3">ملاحظات / السبب</th>
                    <th class="p-3">التاريخ والوقت</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($statusLogs as $log)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $log->id }}</td>
                    <td class="p-3 font-mono text-indigo-400">#{{ $log->booking_id }}</td>
                    <td class="p-3 text-gray-400">{{ $log->previous_status ?? 'بداية' }}</td>
                    <td class="p-3 font-bold text-emerald-400">{{ $log->new_status }}</td>
                    <td class="p-3 text-gray-400">{{ $log->reason ?? '—' }}</td>
                    <td class="p-3 text-gray-500 font-mono text-[11px]">{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- TAB 4: HOLDS -->
    @if($tab === 'holds')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">رمز التثبيت (Hold Token)</th>
                    <th class="p-3">الوحدة</th>
                    <th class="p-3">تاريخ الوصول</th>
                    <th class="p-3">تاريخ المغادرة</th>
                    <th class="p-3">تنتهي الصلاحية</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($dateHolds as $dh)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-amber-300 font-bold">{{ $dh->hold_token }}</td>
                    <td class="p-3 font-bold text-white">{{ $dh->unit->name ?? 'وحدة #'.$dh->unit_id }}</td>
                    <td class="p-3 font-mono text-emerald-300">{{ $dh->check_in_date }}</td>
                    <td class="p-3 font-mono text-rose-300">{{ $dh->check_out_date }}</td>
                    <td class="p-3 text-rose-400 font-mono text-[11px]">{{ $dh->expires_at }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
