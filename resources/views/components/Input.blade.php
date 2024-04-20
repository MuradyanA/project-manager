@props(['class' => '', 'type' => 'text'])
<input
    {{ $attributes->class([
        'p-2' => $type != 'checkbox',
        'p-3 h-4 w-4' => $type == 'checkbox',
        'bg-gray-100',
        'border-2 border-gray-400',
        'h-7 p-4',
        'border-2',
        'rounded-md',
        'border-gray-300',
    ]) }}  type={{ $type }} {{ $attributes }} autocomplete="off">
