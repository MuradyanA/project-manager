<div>
    <button wire:click.prevent="{{ $actionName }}"
        {{ $attributes->merge(['class' => 'w-fit text-center text-md bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-red-700 transition duration-300 ' . $buttonClass]) }}>{{ $name }}</button>
</div>
