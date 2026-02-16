@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 mb-4 p-3 bg-green-50 border border-green-200 rounded']) }}>
        {{ $status }}
    </div>
@endif
