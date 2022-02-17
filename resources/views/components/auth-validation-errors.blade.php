@props(['errors'])

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div {!! $attributes->merge(['class' => 'alert alert-danger']) !!} role="alert">
            {{ $error }}
        </div>
    @endforeach
@endif
