@props(['caption' => '', 'actionName', 'svg' => '', 'class' => '', 'confirmationMessage' => ''])

<button wire:click="{{ $actionName }}"
    {{ $attributes->class(['w-fit text-center text-md  text-gray-800 px-5 py-1 border-2 border-gray-400 ', $class]) }}
    {{ $confirmationMessage ? $attributes->merge(['wire:confirm' => $confirmationMessage]) : '' }}>{!! $svg !!}
    {{ $caption }}
</button>