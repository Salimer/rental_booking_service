@extends('test-dashboard.layout')

@section('title', __('dashboard.nav.api_tester'))

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    
    <!-- Title -->
    <div>
        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
            <span>⚡</span> {{ __('dashboard.tester.title') }}
        </h2>
        <p class="text-sm text-gray-400 mt-1">
            {{ __('dashboard.tester.subtitle') }}
        </p>
    </div>

    <!-- Form Panel -->
    <div class="glass-panel p-6 md:p-8 rounded-2xl border border-emerald-500/20 shadow-xl space-y-6">
        
        <form id="estimateForm" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Unit Selector -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide">
                        {{ __('dashboard.tester.select_unit') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="unit_id" required class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 font-sans">
                        <option value="">-- اختر وحدة سكنية لاختبارها --</option>
                        @foreach($units as $u)
                        <option value="{{ $u->id }}">
                            #{{ $u->id }} - {{ $u->name ?? $u->title }} ({{ $u->property->name ?? 'عقار #'.$u->property_id }}) — {{ number_format($u->base_price ?? 0, 2) }} SAR
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Coupon Code Input -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide">
                        {{ __('dashboard.tester.coupon_code') }}
                    </label>
                    <input type="text" name="coupon_code" placeholder="مثال: SUMMER2026" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-amber-300 font-mono focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Check-in Date -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide">
                        {{ __('dashboard.tester.check_in_date') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="check_in_date" required value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                </div>

                <!-- Check-out Date -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wide">
                        {{ __('dashboard.tester.check_out_date') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="check_out_date" required value="{{ date('Y-m-d', strtotime('+4 days')) }}" class="w-full bg-dark-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 hover:from-emerald-500 hover:to-indigo-500 text-white font-bold text-base shadow-lg shadow-emerald-600/30 transition duration-200 flex items-center justify-center gap-3">
                <span>🚀</span>
                <span>{{ __('dashboard.tester.calculate_btn') }}</span>
            </button>
        </form>

    </div>

    <!-- Results Panel (Hidden initially) -->
    <div id="resultsContainer" class="hidden glass-panel p-6 md:p-8 rounded-2xl border border-indigo-500/30 space-y-6">
        
        <div class="flex items-center justify-between border-b border-gray-800 pb-4">
            <h3 class="text-lg font-bold text-emerald-400 flex items-center gap-2">
                <span>✅</span> {{ __('dashboard.tester.breakdown_title') }}
            </h3>
            <span id="responseStatus" class="px-3 py-1 rounded-full text-xs font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">200 OK</span>
        </div>

        <!-- Calculated Summary Stats -->
        <div id="breakdownCards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Populated via JS -->
        </div>

        <!-- Raw JSON Payload Collapse -->
        <div class="space-y-2">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide">
                {{ __('dashboard.tester.raw_json') }}
            </label>
            <pre id="jsonViewer" class="bg-black/80 text-emerald-400 p-5 rounded-xl text-xs font-mono overflow-x-auto border border-gray-800 max-h-96"></pre>
        </div>

    </div>

    <!-- Error Banner -->
    <div id="errorContainer" class="hidden p-5 rounded-2xl bg-rose-950/60 border border-rose-500/30 text-rose-300 text-sm font-semibold flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        <span id="errorMessage">حدث خطأ في عملية الحساب.</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('estimateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const resultsContainer = document.getElementById('resultsContainer');
        const errorContainer = document.getElementById('errorContainer');
        const jsonViewer = document.getElementById('jsonViewer');
        const breakdownCards = document.getElementById('breakdownCards');
        const errorMessage = document.getElementById('errorMessage');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>⏳</span><span>جاري الحساب والربط بالخادم...</span>';
        resultsContainer.classList.add('hidden');
        errorContainer.classList.add('hidden');

        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route("test-dashboard.estimate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>🚀</span><span>{{ __("dashboard.tester.calculate_btn") }}</span>';

            if (response.ok && result.success) {
                const data = result.data;
                jsonViewer.textContent = JSON.stringify(data, null, 4);

                // Build metric cards dynamically
                breakdownCards.innerHTML = `
                    <div class="glass-card p-4 rounded-xl border border-gray-800">
                        <div class="text-xs text-gray-400">إجمالي السعر الأساسي</div>
                        <div class="text-xl font-bold text-white mt-1">${data.base_total ?? data.subtotal ?? '—'} ${data.currency ?? 'SAR'}</div>
                    </div>
                    <div class="glass-card p-4 rounded-xl border border-gray-800">
                        <div class="text-xs text-gray-400">الخصم المطبق</div>
                        <div class="text-xl font-bold text-amber-400 mt-1">-${data.discount_total ?? data.discount_amount ?? 0} ${data.currency ?? 'SAR'}</div>
                    </div>
                    <div class="glass-card p-4 rounded-xl border border-gray-800">
                        <div class="text-xs text-gray-400">عدد الليالي</div>
                        <div class="text-xl font-bold text-purple-300 mt-1">${data.nights_count ?? data.total_nights ?? '—'} ليلة</div>
                    </div>
                    <div class="glass-card p-4 rounded-xl border border-emerald-500/40 bg-emerald-950/20">
                        <div class="text-xs text-emerald-400 font-bold">المبلغ الإجمالي المالي</div>
                        <div class="text-2xl font-black text-emerald-300 mt-1">${data.total_amount ?? data.grand_total ?? '—'} ${data.currency ?? 'SAR'}</div>
                    </div>
                `;

                resultsContainer.classList.remove('hidden');
            } else {
                errorMessage.textContent = result.error || 'فشلت عملية التقدير. تأكد من تواريخ الحجز أو وجود أسعار مخصصة.';
                errorContainer.classList.remove('hidden');
            }
        } catch (err) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>🚀</span><span>{{ __("dashboard.tester.calculate_btn") }}</span>';
            errorMessage.textContent = 'حدث خطأ في الاتصال بالخادم: ' + err.message;
            errorContainer.classList.remove('hidden');
        }
    });
</script>
@endpush
