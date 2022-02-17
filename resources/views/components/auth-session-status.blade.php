@props(['danger' => '', 'success' => '', 'warning' => '', 'status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success']) }}>
        {{ $status }}
    </div>
@endif

@if ($success)
    <div {{ $attributes->merge(['class' => 'alert alert-success']) }}>
        {{ $success }}
    </div>
@endif

@if ($warning)
    <div {{ $attributes->merge(['class' => 'alert alert-warning']) }}>
        {{ $warning }}
    </div>
@endif

@if ($danger)
    <div {{ $attributes->merge(['class' => 'alert alert-danger']) }}>
        {{ $danger }}
    </div>
@endif
