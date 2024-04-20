@props(['filter'])

<div x-data="{ step: $wire.filter.step }" class="w-fitbg-gray-300 m-3 p-1 rounded-md overflow-hidden ">
    <div id="pageFrame"
        :class="step == 2 ?
            'w-[200%] grid grid-cols-2  -translate-x-1/2 transition-all duration-300 ease-in ' :
            'w-[200%] grid grid-cols-2 -translate-x-1 transition-all duration-300 ease-in'">

        <div class="flex justify-center gap-2">
            <div class="flex flex-col">
                <label class="text-center text-md text-gray-700">Please select a field to filter</label>
                <x-Select :modelName="'filter.currentField.name'" :elems="$filter->fields" />
                <div class="gap-2 flex justify-center mt-3">
                    <button class="w-fit text-center text-md  text-gray-800 px-5 py-1 border-2 border-gray-400"
                        x-transition.duration.500ms x-show="$wire.filter.currentField.name"
                        x-on:click="()=>step = 2; $wire.callFilterMethod('getFieldData')">Next</button>
                </div>
            </div>
        </div>
        <div class="px-2 ">
            <div class="flex gap-2 justify-center">
                @if (count($filter->filterConditions))
                    <x-Select :elems="['and', 'or']" :modelName="'filter.conditionsFormFields.andOr'" />
                @endif
                <x-Select :elems="$filter->currentField['operators']" :modelName="'filter.conditionsFormFields.operator'" />
                <x-Input wire:model="filter.conditionsFormFields.value" type="{{ $filter->currentField['type'] }} " />
            </div>
            <div class="gap-2 flex justify-center mt-3">
                <x-Button x-on:click="step = 1" class="" :actionName="''" :caption="'Previous'" />

                @php
                    $action = "callFilterMethod('addConditions')";
                @endphp
                <x-Button class="" actionName="callFilterMethod('addConditions')" :caption="'Add Condition'" />

            </div>

        </div>

    </div>
    @if (count($filter->filterConditions) > 0)
        @php
            $cols = count($filter->filterConditions) > 1 ? 'grid-cols-5' : 'grid-cols-4';
        @endphp
        <div
            class="w-fit mx-auto border-t-4 border-gray-600 grid {{ $cols }} mt-3 p-2 bg-gray-200 text-center rounded-sm text-gray-700">
            @php
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>';
            @endphp
            @foreach ($filter->filterConditions as $indx => $elem)
                @if ($cols == 'grid-cols-5')
                    <div class="border-b-2 border-gray-400 px-2">
                        @if (!$loop->first)
                            {{ $filter->splitCamelCase($elem[3]) }}
                        @endif
                    </div>
                @endif
                <div class="border-b-2 border-gray-400 px-2">
                    {{ $filter->splitCamelCase($elem[0]) }}
                </div>
                <div class="border-b-2 border-gray-400 font-semibold px-2">
                    {{ $elem[1] }}
                </div>
                <div class="border-b-2 border-gray-400 px-2">
                    {{ $filter->currentField['type'] == 'datetime-local' ? (new DateTime($elem[2]))->format('Y-m-d H:i') : $elem[2] }}
                </div>
                <div class="border-b-2 border-gray-400 px-2">
                    <x-Button :svg="$svg" class="px-1 !border-0"
                        actionName="callFilterMethod('deleteCondition', {{ $indx }})" :caption="''" />
                </div>
            @endforeach
        </div>
    @endif
</div>
