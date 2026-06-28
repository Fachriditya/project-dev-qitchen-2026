@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-700 bg-green-50 px-4 py-3 rounded-xl border border-green-100 shadow-sm']) }}>
        {{ $status }}
    </div>
@endif