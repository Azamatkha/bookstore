@if (session('success') || session('error'))
    <div class="mb-6 rounded-2xl border px-4 py-3 text-sm font-medium {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">
        {{ session('success') ?? session('error') }}
    </div>
@endif
