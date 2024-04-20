@props(['elems', 'modelName'])
<select class="p-1 rounded-md bg-gray-100 border-2 border-gray-400 px-2" wire:model="{{ $modelName }}">
    @foreach ($elems as $elem)
        @if (is_array($elem))
            <option value="{{ $elem[0] }}">{{ $elem[1] }}</option>
        @else
            <option value="{{ $elem }}">{{ $elem }}</option>
        @endif
    @endforeach
</select>
