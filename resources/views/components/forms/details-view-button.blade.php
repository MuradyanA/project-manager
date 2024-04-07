@props(['caption' => '', 'actionName', 'svg' => '', 'class' => '', 'confirmationMessage' => ''])
<button wire:click="{{ $actionName }}"
    {{ $attributes->class(['w-fit text-center text-md bg-gray-500 text-white px-3 py-1 hover:bg-red-700 transition duration-300 ', $class]) }}
    {{ $confirmationMessage ? $attributes->merge(['wire:confirm' => $confirmationMessage]) : '' }}>{!! $svg !!}
    {{ $caption }}
</button>
