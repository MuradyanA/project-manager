@props(['section'])

@php
    extract($section->getData());
@endphp

<div>
    <p class="font-bold"> {{ $fieldName }}: </p>
    <p>{{ $text }}</p>
</div>
