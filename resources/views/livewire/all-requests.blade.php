<div>
    <div class="italic sticky top-20">
        <div class="w-full h-auto bg-[#367a76]  p-5 text-center">
            <a href="/projects/{{ $project->id }}"
                class="text-gray-100 text-2xl px-[2%] cursor-pointer duration-300">Project</a>
            <a href="/projects/{{ $project->id }}/sprints"
                class="text-gray-100 text-2xl px-[2%] cursor-pointer duration-300">Sprint</a>
        </div>
    </div>
    <h1 class="text-gray-100 text-center text-5xl font-light italic my-[3%]">Details of {{ $project->name }}</h1>
    <p class="w-[40%] mx-auto text-gray-100 text-center text-2xl font-semibold italic my-[3%]">
        {{ $project->description }}</p>
    <hr class="w-[60%] mx-auto border-2 rounded-full border-gray-200">
    <div class="text-center my-[2%]">
        @if (!$isOpenedNewRequestForm)
            <button wire:click="toggleNewRequestWindow()"
                class="bg-blue-700 font-semibold p-2 hover:bg-blue-900 duration-300 rounded-sm text-gray-200">New
                Request</button>
        @else
            <div class="mx-auto justify-center items-center w-[20%] bg-gray-300 rounded-md">
                <div class="bg-gray-300 text-start p-2"><button wire:click="closeNewRequestForm()"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="p-2 text-center w-full pb-[5%] italic text-xl font-semibold">New Request</p>
                <form wire:submit="save()" class="flex rounded-sm flex-col w-full p-3 bg-gray-100">
                    <label class="text-start text-gray-600 text-lg font-semibold">Request Title</label>
                    <input wire:model.live="form.title" placeholder="Title"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="text">
                    <label class="text-start text-gray-600 text-lg font-semibold">Request</label>
                    <textarea wire:model.live="form.request" placeholder="Request Details..."
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md" type="text"></textarea>
                    @error('newRequestTitle')
                        <span class="block text-red-700 text-center">{{ $message }}</span>
                    @enderror
                    @error('newRequest')
                        <span class="block text-red-700 text-center">{{ $message }}</span>
                    @enderror
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-[#0077c5] hover:bg-[#004f82] duration-300 text-center w-full font-bold rounded-md text-lg p-2 mt-[3%] text-gray-100 ">Create</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
    <div class="w-full flex items-center justify-center">
        <table class="table-auto bg-yellow-200 rounded-md ">
            <thead class="tracking-wide ">
                <tr>
                    <th class="p-4 font-medium">Requester ID</th>
                    <th class="p-4 font-medium">Request Title</th>
                    <th class="p-4 font-medium">Request Body</th>
                    <th class="p-4 font-medium">Creation Date</th>
                    <th class="p-4 font-medium">Actions</th>
                </tr>
                @foreach ($requests as $request)
                    <tr
                        class="{{ !in_array($request->id, $selectedRows) ? 'bg-white border-b' : 'border-b bg-gray-300' }}">
                        <td class="p-4">{{ $request->requesterId }}</td>
                        <td class="p-4"><a class="underline italic hover:text-gray-500 duration-300"
                                href="/projects/view/{{ $request->id }}">{{ $request->title }}</a></td>
                        <td class="p-4 max-w-[10ch] whitespace-nowrap overflow-hidden overflow-ellipsis">
                            {{ $request->request }}</td>
                        <td class="p-4">{{ $request->created_at }}</td>
                        <td class="p-4">
                            @if (!in_array($request->id, $selectedRows))
                                <button wire:click="toggleSelect({{ $request->id }})" title="Select"
                                    class="bg-green-600 rounded-full p-2"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 text-gray-200">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                            @else
                                <button wire:click="toggleSelect({{ $request->id }})" title="Cancel"
                                    class="bg-orange-400 p-2 rounded-full"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 text-gray-100">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                            <button wire:click="rejectRequest({{ $request->id }})" title="Reject Request"
                                class="bg-red-600 rounded-full p-2"><svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 text-gray-200">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>

                            </button>

                        </td>
                    </tr>
                @endforeach
            </thead>
        </table>
    </div>
    @if (count($selectedRows) > 0)
        <div
            class="fixed w-[20%] text-center  p-3 bottom-0 left-1/2 transform -translate-x-1/2  bg-sky-900 text-white p-4 rounded shadow">
            <p class="font-semibold text-xl italic">Actions with Selected</p>
            <hr class="my-[6%]">
            <div>
                @if (isset($noActiveSprints))
                    <p class="text-red-500 font-bold italic pb-[5%]">
                        {{ $noActiveSprints }}
                    </p>
                @endif
                @if (isset($taskAlreadyInSprint))
                    <p class="text-red-500 font-bold italic pb-[5%]">
                        {{ $taskAlreadyInSprint }}
                    </p>
                @endif
            </div>
            <button wire:click="toggleNewSprintForm()" class="italic p-2 text-center bg-green-500"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create Sprint</span></button>
            <button wire:click="addToSprint()" class="italic p-2 text-center bg-orange-800"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>
                <span>Add to Sprint</span></button>
        </div>
    @endif
    @if ($isOpenedNewSprintForm)
        <div class="bg-blue-600 fixed w-[15%] top-[35%] bottom-[65%] left-1/2 -translate-x-1/2">
            <div class="bg-gray-500"><button wire:click="closeNewSprintForm"><svg xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="bg-gray-500 text-center p-4">
                <p class="italic text-gray-200 font-bold text-2xl">New Sprint</p>
            </div>
            <form wire:submit="createSprintAndTask()" class="flex flex-col bg-gray-200 p-5">
                <label class="text-gray-600 text-lg font-semibold" for="">Sprint Start</label>
                <input wire:model="form.sprintStart"
                    class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                    type="date">
                <label class="text-gray-600 text-lg font-semibold" for="">Sprint End</label>
                <input wire:model="form.sprintEnd"
                    class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                    type="date">
                @error('sprintStart')
                    <span class="block text-red-700 text-center">{{ $message }}</span>
                @enderror
                @error('sprintEnd')
                    <span class="block text-red-700 text-center">{{ $message }}</span>
                @enderror
                
                @error('ValidationError')
                <div>
                    <span class="block text-red-700 text-center">{{ $message }}</span>  
                </div>
                @enderror
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-[#0077c5] hover:bg-[#004f82] duration-300 text-center w-full font-bold rounded-md text-lg p-2 mt-[10%] text-gray-100 ">Create</button>
                </div>
            </form>
            {{-- <div>{{ $sprints->id }}</div> --}}
        </div>
    @endif
</div>
