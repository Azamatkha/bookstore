@props(['status'])

@php
    $statusValue = $status instanceof \App\Enums\OrderStatus ? $status->value : (string) $status;
    $classes = match ($statusValue) {
        'pending' => 'bg-amber-100 text-amber-800',
        'confirmed' => 'bg-sky-100 text-sky-800',
        'shipping' => 'bg-violet-100 text-violet-800',
        'delivered' => 'bg-emerald-100 text-emerald-800',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {$classes}"]) }}>
    {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
</span>
