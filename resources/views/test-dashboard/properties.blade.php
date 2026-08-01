@extends('test-dashboard.layout')

@section('title', __('dashboard.nav.properties'))

@section('content')
<div class="space-y-6">
    
    <!-- Title -->
    <div>
        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
            <span>🏢</span> {{ __('dashboard.nav.properties') }}
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            معاينة العقارات، الوحدات السكنية، جدول أسعار الليلة، فترات المنع والحظر اليدوي.
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-gray-800 pb-3">
        <a href="{{ route('test-dashboard.properties', ['tab' => 'properties']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'properties' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            🏢 {{ __('dashboard.tabs.properties') }} ({{ count($properties) }})
        </a>
        <a href="{{ route('test-dashboard.properties', ['tab' => 'units']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'units' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            🔑 {{ __('dashboard.tabs.units') }} ({{ count($units) }})
        </a>
        <a href="{{ route('test-dashboard.properties', ['tab' => 'prices']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'prices' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            🏷️ {{ __('dashboard.tabs.prices') }} ({{ count($prices) }})
        </a>
        <a href="{{ route('test-dashboard.properties', ['tab' => 'availabilities']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'availabilities' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            🚫 {{ __('dashboard.tabs.availabilities') }} ({{ count($availabilities) + count($manualLocks) }})
        </a>
        <a href="{{ route('test-dashboard.properties', ['tab' => 'amenities']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'amenities' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            ✨ {{ __('dashboard.tabs.amenities') }} ({{ count($amenities) }})
        </a>
    </div>

    <!-- TAB 1: PROPERTIES -->
    @if($tab === 'properties')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">اسم العقار</th>
                    <th class="p-3">المؤسسة / المالك</th>
                    <th class="p-3">المدينة / الدولة</th>
                    <th class="p-3">النوع</th>
                    <th class="p-3">التقييم</th>
                    <th class="p-3">تاريخ الإنشاء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($properties as $p)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $p->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $p->title ?? $p->name ?? 'عقار #'.$p->id }}</td>
                    <td class="p-3 text-indigo-400 font-medium">{{ $p->org->name ?? ('مؤسسة #'.$p->org_id) }}</td>
                    <td class="p-3 text-gray-300">{{ $p->city->name ?? '—' }} / {{ $p->country->name ?? '—' }}</td>
                    <td class="p-3"><span class="px-2.5 py-1 rounded-md bg-gray-800 text-gray-300">{{ $p->type->name ?? 'سكني' }}</span></td>
                    <td class="p-3 text-amber-400 font-bold">⭐ {{ $p->star_rating ?? $p->avg_rating ?? 'N/A' }}</td>
                    <td class="p-3 text-gray-500 font-mono text-[11px]">{{ $p->created_at ? $p->created_at->format('Y-m-d') : '—' }}</td>
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

    <!-- TAB 2: UNITS -->
    @if($tab === 'units')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">اسم الوحدة</th>
                    <th class="p-3">العقار التابع له</th>
                    <th class="p-3">أقصى ضيوف</th>
                    <th class="p-3">غرف النوم</th>
                    <th class="p-3">دورات المياه</th>
                    <th class="p-3">السعر الأساسي</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($units as $u)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $u->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $u->title ?? $u->name ?? 'وحدة #'.$u->id }}</td>
                    <td class="p-3 text-indigo-400">{{ $u->property->title ?? $u->property->name ?? 'عقار #'.$u->property_id }}</td>
                    <td class="p-3 font-semibold text-gray-300">👥 {{ $u->max_guests ?? '—' }}</td>
                    <td class="p-3">🛏️ {{ $u->bedrooms_count ?? $u->bedrooms ?? '—' }}</td>
                    <td class="p-3">🚿 {{ $u->bathrooms_count ?? $u->bathrooms ?? '—' }}</td>
                    <td class="p-3 font-bold text-emerald-400">{{ number_format($u->base_price ?? 0, 2) }} {{ $u->currency ?? 'SAR' }}</td>
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

    <!-- TAB 3: PRICES -->
    @if($tab === 'prices')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">العنصر (الوحدة / العقار)</th>
                    <th class="p-3">نوع السعر</th>
                    <th class="p-3">من تاريخ</th>
                    <th class="p-3">إلى تاريخ</th>
                    <th class="p-3">السعر (SAR)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($prices as $pr)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $pr->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $pr->priceable->title ?? $pr->priceable->name ?? $pr->name ?? 'عنصر #'.$pr->priceable_id }}</td>
                    <td class="p-3"><span class="px-2 py-1 rounded bg-purple-500/20 text-purple-300 font-semibold">{{ $pr->price_type }}</span></td>
                    <td class="p-3 font-mono text-indigo-300">{{ $pr->start_date ? $pr->start_date->format('Y-m-d') : '—' }}</td>
                    <td class="p-3 font-mono text-indigo-300">{{ $pr->end_date ? $pr->end_date->format('Y-m-d') : '—' }}</td>
                    <td class="p-3 font-extrabold text-emerald-400">{{ number_format($pr->price_sar ?? 0, 2) }} SAR</td>
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

    <!-- TAB 4: AVAILABILITIES & LOCKS -->
    @if($tab === 'availabilities')
    <div class="space-y-6">
        <!-- Manual Locks -->
        <div class="glass-panel p-6 rounded-2xl space-y-3">
            <h3 class="text-sm font-bold text-rose-400 flex items-center gap-2">🔒 الحظر اليدوي والإغلاقات (Manual Locks)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs text-gray-300">
                    <thead class="bg-gray-800/80 text-gray-400 font-semibold">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">الوحدة</th>
                            <th class="p-3">من تاريخ</th>
                            <th class="p-3">إلى تاريخ</th>
                            <th class="p-3">السبب</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($manualLocks as $ml)
                        <tr class="hover:bg-gray-800/30 transition">
                            <td class="p-3 font-mono text-gray-400">#{{ $ml->id }}</td>
                            <td class="p-3 font-bold text-white">{{ $ml->unit->name ?? 'وحدة #'.$ml->unit_id }}</td>
                            <td class="p-3 font-mono text-amber-300">{{ $ml->start_date }}</td>
                            <td class="p-3 font-mono text-amber-300">{{ $ml->end_date }}</td>
                            <td class="p-3 text-gray-400">{{ $ml->reason ?? 'صيانة / إغلاق' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">لا توجد إغلاقات دستورية يدوية حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- TAB 5: AMENITIES -->
    @if($tab === 'amenities')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($amenities as $am)
            <div class="glass-card p-4 rounded-xl flex items-center gap-3 border border-gray-800">
                <div class="w-10 h-10 rounded-lg bg-indigo-600/20 text-indigo-400 flex items-center justify-center font-bold text-lg">
                    ✨
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm">{{ $am->name ?? $am->title }}</h4>
                    <p class="text-xs text-gray-500">رمز المعرف: #{{ $am->id }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center p-8 text-gray-500">{{ __('dashboard.empty') }}</div>
            @endforelse
        </div>
    </div>
    @endif

</div>
@endsection
