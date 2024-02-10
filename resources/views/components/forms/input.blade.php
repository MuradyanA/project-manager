@props(['class' => '', 'type' => 'text', 'name', 'caption'])
<div {{ $attributes->class(['flex my-2 flex-col w-full', $class]) }}>
    <div class="flex flex-col">
        <label class="font-semibold text-gray-300 w-[30%]">{{ $caption }}</label>
        <input
        {{ $attributes->class([
                'p-2' => $type != 'checkbox',
                'p-3 h-4 w-4' => $type == 'checkbox',
                'bg-[#0a101e]',
                'h-7 p-4',
                'text-gray-400',
                'font-bold',
                'border-2',
                'rounded-md',   
                'border-[#2b3269]'
            ]) }}
            type={{ $type }} name="{{ $name }}" {{ $attributes }} autocomplete="off">
    </div>

    <div class=" w-full">
        @error($name)
            <span class="block text-red-700 text-end">{{ $message }}</span>
        @enderror
    </div>
</div>
