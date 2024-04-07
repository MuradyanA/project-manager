@props(['header'])
<div class="bg-gray-200 p-2">
    <h4 class="font-semibold">{{ $header }}</h4>
    <hr class="h-0.5 bg-gray-300">
    {{ $slot }}
</div>
