<div class="h-auto z-10 mx-auto justify-center items-center w-[30%] bg-gray-200 rounded-md">
    <div class="p-4 bg-gray-100">
        <p class="italic text-xl text-center font-semibold text-gray-600">Choose users for assignment</p>
    </div>
    <div class="w-full ml-[1%] py-[2%]">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6 top-1/2 transform -translate-y-1/2 left-2 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input wire:model="userName" type="text" class="p-1 pl-8 border-2 border-gray-400 rounded-md w-[70%]">
        <button wire:click="searchUser()"
            class="bg-green-600 p-1 border-2 border-green-600 text-xl w-[25%] rounded-md text-gray-100">Search</button>
    </div>
    @if ($searchResults)
        <div class="mt-[1%] h-fit">
            @foreach ($searchResults as $item)
                @if (!in_array($item, $selectedItems))
                    <button wire:click="addItemToList({{json_encode($item)}})"
                        class="p-2 my-[1%] bg-white w-full text-lg font-semibold text-gray-700 italic">
                        @foreach ($fieldsToShow as $field)
                            {{ $item[$field] }}
                            @if (!$loop->last)
                                |
                            @endif
                        @endforeach
                    </button>
                @endif
            @endforeach
        </div>

    @endif
    @if ($selectedItems)
        <div class="flex flex-wrap">
            @foreach ($selectedItems as $item)
                <div class="ml-[1%] p-2 my-[1%] bg-white text-lg font-semibold text-gray-700 italic rounded-md">
                    <button class="inline-block" wire:click="removeUserFromList({{json_encode($item)}})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6  pt-[2%]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <p class="inline-block">
                        @foreach ($fieldsToShow as $field)
                            {{ $item[$field] }}
                            @if (!$loop->last)
                                |
                            @endif
                        @endforeach
                    </p>
                </div>
            @endforeach
        </div>
    @endif
    <div>
    </div>
    {{-- <div class="flex justify-center">
        @if ($selectedItems)
            <button wire:click="assignUsersToTask()"
                class="p-2 text-xl font-semibold bg-blue-500 text-gray-200 rounded-sm my-[5%] px-5 hover:bg-blue-700 duration-300">Assign</button>
        @endif
    </div> --}}
</div>
