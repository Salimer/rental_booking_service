@extends('test-dashboard.layout')

@section('title', __('dashboard.nav.coupons'))

@section('content')
<div class="space-y-6">
    
    <!-- Title -->
    <div>
        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
            <span>🎟️</span> {{ __('dashboard.nav.coupons') }}
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            إدارة ومعاينة كودات وقسائم الخصومات العامة، سجلات الاستخدام، وخصومات بوابات الدفع المسجلة.
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-gray-800 pb-3">
        <a href="{{ route('test-dashboard.coupons', ['tab' => 'coupons']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'coupons' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            🎟️ {{ __('dashboard.tabs.coupons_list') }} ({{ count($coupons) }})
        </a>
        <a href="{{ route('test-dashboard.coupons', ['tab' => 'usages']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'usages' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            📊 سجل الاستخدام ({{ count($usages) }})
        </a>
        <a href="{{ route('test-dashboard.coupons', ['tab' => 'gateways']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'gateways' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            💳 {{ __('dashboard.tabs.gateway_discounts') }} ({{ count($gatewayDiscounts) }})
        </a>
    </div>

    <!-- TAB 1: COUPONS -->
    @if($tab === 'coupons')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">كود الخصم</th>
                    <th class="p-3">نوع الخصم</th>
                    <th class="p-3">قيمة الخصم</th>
                    <th class="p-3">عدد مرات الاستخدام</th>
                    <th class="p-3">تاريخ الانتهاء</th>
                    <th class="p-3">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($coupons as $c)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $c->id }}</td>
                    <td class="p-3 font-mono font-extrabold text-amber-300 text-sm tracking-wider">{{ $c->code }}</td>
                    <td class="p-3 font-medium text-gray-300">{{ in_array($c->discount_type, ['percent', 'percentage']) ? 'نسبة مئوية (%)' : 'مبلغ ثابت' }}</td>
                    <td class="p-3 font-bold text-emerald-400">
                        {{ in_array($c->discount_type, ['percent', 'percentage']) ? $c->discount.'%' : number_format($c->discount ?? $c->discount_value ?? 0, 2).' SAR' }}
                    </td>
                    <td class="p-3 text-gray-300 font-mono">{{ $c->used_count ?? 0 }} / {{ $c->max_uses ?? '∞' }}</td>
                    <td class="p-3 font-mono text-gray-400 text-[11px]">{{ $c->expire_date ?? $c->expires_at ?? 'غير محدد' }}</td>
                    <td class="p-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $c->status ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                            {{ $c->status ? __('dashboard.status.active') : __('dashboard.status.inactive') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- TAB 2: USAGES -->
    @if($tab === 'usages')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">الكوبون المستخدم</th>
                    <th class="p-3">رقم الحجز</th>
                    <th class="p-3">المستخدم</th>
                    <th class="p-3">قيمة الخصم المطبقة</th>
                    <th class="p-3">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($usages as $u)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $u->id }}</td>
                    <td class="p-3 font-mono text-amber-300 font-bold">{{ $u->coupon->code ?? 'الكوبون #'.$u->coupon_id }}</td>
                    <td class="p-3 font-mono text-indigo-400">#{{ $u->booking_id }}</td>
                    <td class="p-3 text-gray-300">User #{{ $u->user_id }}</td>
                    <td class="p-3 font-bold text-emerald-400">{{ number_format($u->discount_amount, 2) }} SAR</td>
                    <td class="p-3 font-mono text-gray-500 text-[11px]">{{ $u->created_at ? $u->created_at->format('Y-m-d H:i') : '—' }}</td>
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

    <!-- TAB 3: GATEWAYS -->
    @if($tab === 'gateways')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">بوابة الدفع</th>
                    <th class="p-3">نسبة / قيمة الخصم</th>
                    <th class="p-3">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($gatewayDiscounts as $gd)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $gd->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $gd->gateway_name }}</td>
                    <td class="p-3 font-bold text-emerald-400">{{ $gd->discount_percentage }}%</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300">نشط</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-500">{{ __('dashboard.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
