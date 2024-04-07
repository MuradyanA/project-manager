<div>
    <livewire:notification />
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
        {{ $project->description }}
    </p>
    {{-- <hr class="w-[60%] mx-auto border-2 rounded-full border-gray-200"> --}}
    <div class="text-center my-[2%]">
        @if (!$isOpenedNewRequestForm)
            <button wire:click="toggleNewRequestWindow()"
                class="bg-blue-700 font-semibold p-2 hover:bg-blue-900 duration-300 rounded-sm text-gray-200">New
                Request</button>
        @else
            <div class="flex justify-center">
                <x-forms.form :builder="$this->createChangeRequestBuilder()" />
            </div>
        @endif
    </div>
    <livewire:table-view :key="$tableKey" tableClass="{{ App\Services\TableViews\ChangeRequestsTable::class }}" />
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
        <x-forms.form :builder="$this->createBuilder()" />
    @endif
    <div class="w-full">
        @if ($showDetails)
            <x-ViewData.details-view :builder="$this->getDetailsViewBuilder()" />
        @endif
    </div>
</div>
</div>
