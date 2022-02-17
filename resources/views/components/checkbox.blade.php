@props(['checked' => ''])

<input type="checkbox" {!! $attributes->merge(['class' => 'form-check-input']) !!} @if($checked) checked @endif>
