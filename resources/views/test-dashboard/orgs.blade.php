@extends('test-dashboard.layout')

@section('title', __('dashboard.nav.orgs'))

@section('content')
<div class="space-y-6">
    
    <!-- Title -->
    <div>
        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
            <span>🏛️</span> {{ __('dashboard.nav.orgs') }}
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            سجل المؤسسات، المؤجرين، أعضاء طاقم العمل، نسب التكلفة، ودورية تسوية العائدات.
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-gray-800 pb-3">
        <a href="{{ route('test-dashboard.orgs', ['tab' => 'orgs']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'orgs' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            🏛️ {{ __('dashboard.tabs.orgs_list') }} ({{ count($orgs) }})
        </a>
        <a href="{{ route('test-dashboard.orgs', ['tab' => 'staff']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'staff' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            👥 {{ __('dashboard.tabs.org_staff') }} ({{ count($orgStaff) }})
        </a>
        <a href="{{ route('test-dashboard.orgs', ['tab' => 'settings']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition {{ $tab === 'settings' ? 'bg-indigo-600 text-white shadow-lg' : 'glass-card text-gray-400 hover:text-white' }}">
            ⚙️ {{ __('dashboard.tabs.org_settings') }} ({{ count($orgSettings) }})
        </a>
    </div>

    <!-- TAB 1: ORGS -->
    @if($tab === 'orgs')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">اسم المؤسسة / المؤجر</th>
                    <th class="p-3">البريد الإلكتروني</th>
                    <th class="p-3">الهاتف</th>
                    <th class="p-3">العملة المفضلة</th>
                    <th class="p-3">تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($orgs as $o)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $o->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $o->name }}</td>
                    <td class="p-3 font-mono text-indigo-300">{{ $o->email ?? '—' }}</td>
                    <td class="p-3 font-mono text-gray-300">{{ $o->phone ?? '—' }}</td>
                    <td class="p-3 font-bold text-amber-400">{{ $o->preferred_currency ?? 'SAR' }}</td>
                    <td class="p-3 font-mono text-gray-500 text-[11px]">{{ $o->created_at ? $o->created_at->format('Y-m-d') : '—' }}</td>
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

    <!-- TAB 2: STAFF -->
    @if($tab === 'staff')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">اسم الموظف</th>
                    <th class="p-3">المؤسسة</th>
                    <th class="p-3">البريد الإلكتروني</th>
                    <th class="p-3">الدور / الصلاحيات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($orgStaff as $st)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $st->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $st->name ?? $st->user_id }}</td>
                    <td class="p-3 font-medium text-indigo-400">{{ $st->org->name ?? 'مؤسسة #'.$st->org_id }}</td>
                    <td class="p-3 font-mono text-gray-300">{{ $st->email ?? '—' }}</td>
                    <td class="p-3"><span class="px-2.5 py-1 rounded bg-purple-500/20 text-purple-300 font-semibold">{{ $st->role ?? 'عضو طاقم' }}</span></td>
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

    <!-- TAB 3: SETTINGS -->
    @if($tab === 'settings')
    <div class="glass-panel p-6 rounded-2xl overflow-x-auto">
        <table class="w-full text-right text-xs text-gray-300">
            <thead class="bg-gray-800/80 text-gray-400 font-semibold uppercase">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">المؤسسة</th>
                    <th class="p-3">نسبة العمولة (%)</th>
                    <th class="p-3">دورية التسوية (Payout Frequency)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($orgSettings as $os)
                <tr class="hover:bg-gray-800/30 transition">
                    <td class="p-3 font-mono text-gray-400">#{{ $os->id }}</td>
                    <td class="p-3 font-bold text-white">{{ $os->org->name ?? 'مؤسسة #'.$os->org_id }}</td>
                    <td class="p-3 font-bold text-emerald-400">{{ $os->commission_rate ?? '0' }}%</td>
                    <td class="p-3 font-mono text-amber-300">{{ $os->payout_frequency ?? 'أسبوعي' }}</td>
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
