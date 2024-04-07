<div class=" bg-gray-300 my-[2%] p-2">
    <div class="flex justify-center">
        <div class="flex border-2 border-gray-400 w-[40%]">
            <input class="w-full p-2 border-0" type="text" wire:model.live.debounce.500ms="search">
            @if ($search)
                <div class="w-fit flex items-center bg-white">
                    <button wire:click="clearSearchInput()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            <div class="w-fit flex items-center bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <div class="flex flex-rows justify-start p-2 gap-1 w-[40%]">
            @foreach ($tableClass::getToolbarButtons() as $button)
                @php
                    echo $button->render();
                @endphp
            @endforeach
        </div>
    </div>
    <div class="flex flex-rows justify-center">
        <table>
            <tr class="bg-gray-500 text-gray-200 rounded-md">
                <th></th>
                @foreach ($tableClass::$headers as $header)
                    <th class="p-2 text-xl text-center font-semibold">{{ $header }}</th>
                @endforeach
            </tr>

            @foreach ($data as $row)
                <tr @class([
                    'odd:bg-white even:bg-gray-200' => !in_array($row->id, $selectedItems),
                    'bg-blue-400' => in_array($row->id, $selectedItems),
                    'text-white' => in_array($row->id, $selectedItems),
                ])>
                    <td class="p-2">
                        {{-- <input wire:click="toggleSelection($row->id)" class="w-4 h-4 items-center"
                            type="checkbox"> --}}
                        <input wire:model.live="selectedItems" value="{{ $row->id }}" type="checkbox">
                    </td>
                    @foreach ($row as $key => $value)
                        @if ($tableClassInstance->isNotHidden($key))
                            <td class="text-start p-3">
                                @if ($tableClassInstance->fieldHasLink($key))
                                    {!! $tableClassInstance->setLinkParams($row, $key, $value)->make() !!}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>
    <div class="flex justify-center p-2">
        {{ $data->links(data: ['scrollTo' => false]) }}
    </div>
    {{-- <p>
        @php
            echo json_encode($selectedItems);
        @endphp

    </p> --}}
</div>
