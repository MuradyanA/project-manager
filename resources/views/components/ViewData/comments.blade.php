@props(['section'])

@php
    extract($section->getData());
@endphp
<div>
    <div class="overflow-scroll max-h-60">
        <livewire:comment :commentableType="$commentableType" :commentableId="$commentableId" />
    </div>
</div>
