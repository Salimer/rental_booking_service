@extends('test-dashboard.layout')

@section('title', __('dashboard.nav.overview'))

@section('content')
<div class="space-y-8">
    
    <!-- Top Header Title -->
    <div>
        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
            <span>📊</span> {{ __('dashboard.nav.overview') }}
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            ملخص البيانات والإحصائيات الحالية المخزنة في قاعدة البيانات الخاصة بخدمة التأجير.
        </p>
    </div>

    <!-- Aggregate Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
        <!-- Total Properties Card -->
        <div class="glass-card p-5 rounded-2xl border border-indigo-500/20 flex flex-col justify-between relative overflow-hidden group hover:border-indigo-500/40 transition">
            <div class="flex items-center justify-between text-gray-400 text-xs font-semibold">
                <span>{{ __('dashboard.metrics.total_properties') }}</span>
                <span class="text-xl">🏢</span>
            </div>
            <div class="mt-4 text-3xl font-black text-white">
                {{ number_format($stats['total_properties']) }}
            </div>
            <div class="mt-2 text-xs text-indigo-400">عقارات مسجلة بالخدمة</div>
        </div>

        <!-- Total Units Card -->
        <div class="glass-card p-5 rounded-2xl border border-purple-500/20 flex flex-col justify-between relative overflow-hidden group hover:border-purple-500/40 transition">
            <div class="flex items-center justify-between text-gray-400 text-xs font-semibold">
                <span>{{ __('dashboard.metrics.total_units') }}</span>
                <span class="text-xl">🔑</span>
            </div>
            <div class="mt-4 text-3xl font-black text-white">
                {{ number_format($stats['total_units']) }}
            </div>
            <div class="mt-2 text-xs text-purple-400">وحدات سكنية شواغر</div>
        </div>

        <!-- Total Bookings Card -->
        <div class="glass-card p-5 rounded-2xl border border-pink-500/20 flex flex-col justify-between relative overflow-hidden group hover:border-pink-500/40 transition">
            <div class="flex items-center justify-between text-gray-400 text-xs font-semibold">
                <span>{{ __('dashboard.metrics.total_bookings') }}</span>
                <span class="text-xl">📅</span>
            </div>
            <div class="mt-4 text-3xl font-black text-white">
                {{ number_format($stats['total_bookings']) }}
            </div>
            <div class="mt-2 text-xs text-pink-400">حجوزات مسجلة</div>
        </div>

        <!-- Active Coupons Card -->
        <div class="glass-card p-5 rounded-2xl border border-emerald-500/20 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-500/40 transition">
            <div class="flex items-center justify-between text-gray-400 text-xs font-semibold">
                <span>{{ __('dashboard.metrics.active_coupons') }}</span>
                <span class="text-xl">🎟️</span>
            </div>
            <div class="mt-4 text-3xl font-black text-white">
                {{ number_format($stats['active_coupons']) }}
            </div>
            <div class="mt-2 text-xs text-emerald-400">كوبونات خصم تابعة</div>
        </div>

        <!-- Registered Orgs Card -->
        <div class="glass-card p-5 rounded-2xl border border-amber-500/20 flex flex-col justify-between relative overflow-hidden group hover:border-amber-500/40 transition">
            <div class="flex items-center justify-between text-gray-400 text-xs font-semibold">
                <span>{{ __('dashboard.metrics.total_orgs') }}</span>
                <span class="text-xl">🏛️</span>
            </div>
            <div class="mt-4 text-3xl font-black text-white">
                {{ number_format($stats['total_orgs']) }}
            </div>
            <div class="mt-2 text-xs text-amber-400">مؤسسات ومُؤجّرين</div>
        </div>

        <!-- Total Revenue Card -->
        <div class="glass-card p-5 rounded-2xl border border-cyan-500/20 flex flex-col justify-between relative overflow-hidden group hover:border-cyan-500/40 transition">
            <div class="flex items-center justify-between text-gray-400 text-xs font-semibold">
                <span>{{ __('dashboard.metrics.revenue') }}</span>
                <span class="text-xl">💵</span>
            </div>
            <div class="mt-4 text-2xl font-black text-cyan-400">
                {{ number_format($stats['total_revenue'], 2) }}
            </div>
            <div class="mt-2 text-xs text-cyan-500">حجوزات مؤكدة ومكتملة</div>
        </div>
    </div>

    <!-- Recent Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Bookings Panel -->
        <div class="glass-panel p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>📌</span> أحدث الحجوزات المسجلة
                </h3>
                <a href="{{ route('test-dashboard.bookings') }}" class="text-xs text-indigo-400 hover:underline">عرض الكل ←</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs text-gray-300">
                    <thead class="bg-gray-800/60 text-gray-400 uppercase font-semibold">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">{{ __('dashboard.headers.unit') }}</th>
                            <th class="p-3">{{ __('dashboard.headers.total_amount') }}</th>
                            <th class="p-3">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($recentBookings as $b)
                        <tr class="hover:bg-gray-800/30 transition">
                            <td class="p-3 font-mono text-gray-400">#{{ $b->id }}</td>
                            <td class="p-3 font-medium text-white">{{ $b->unit->name ?? ($b->property->name ?? 'وحدة #'.$b->unit_id) }}</td>
                            <td class="p-3 font-bold text-emerald-400">{{ number_format($b->unit_price * ($b->nights_count ?? 1), 2) }} {{ $b->currency }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $b->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : '' }}
                                    {{ $b->status === 'pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : '' }}
                                    {{ $b->status === 'cancelled' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : '' }}">
                                    {{ __('dashboard.status.' . strtolower($b->status)) ?? $b->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Status Logs Panel -->
        <div class="glass-panel p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>📜</span> سجل التغييرات والأحداث الأخيرة
                </h3>
                <a href="{{ route('test-dashboard.bookings', ['tab' => 'status_logs']) }}" class="text-xs text-indigo-400 hover:underline">عرض الكل ←</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs text-gray-300">
                    <thead class="bg-gray-800/60 text-gray-400 uppercase font-semibold">
                        <tr>
                            <th class="p-3">معرف الحجز</th>
                            <th class="p-3">الحالة القديمة</th>
                            <th class="p-3">الحالة الجديدة</th>
                            <th class="p-3">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($recentLogs as $log)
                        <tr class="hover:bg-gray-800/30 transition">
                            <td class="p-3 font-mono text-indigo-400">#{{ $log->booking_id }}</td>
                            <td class="p-3 text-gray-400">{{ $log->previous_status ?? '—' }}</td>
                            <td class="p-3 font-bold text-emerald-400">{{ $log->new_status }}</td>
                            <td class="p-3 text-gray-500 font-mono text-[11px]">{{ $log->created_at ? $log->created_at->format('Y-m-d H:i') : '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
