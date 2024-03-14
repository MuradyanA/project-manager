<div>
    <div class="italic sticky top-20">
        <div class="w-full h-auto bg-[#367a76]  p-5 text-center">
            <a href="/projects/{{ $project->id }}"
                class="text-gray-100 text-2xl px-[2%] cursor-pointer duration-300">Project</a>
            <a href="/projects/{{ $project->id }}/sprints"
                class="text-gray-100 text-2xl px-[2%] cursor-pointer duration-300">Sprint</a>
        </div>
    </div>
    <div class="flex flex-col bg-[#a1d5cc] p-8 text-center justify-center gap-2">
        <h1 class="italic text-3xl font-bold text-center text-gray-800">Current Sprint</h1>
        <div>
            <select wire:model.change='currentSprintID'
                class="bg-gray-200 rounded-lg w-[4%] text-center justify-center p-2" name="" id="">
                @foreach ($sprints as $sprint)
                    <option class="" value="{{ $sprint->id }}">{{ $sprint->sprint }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-center">
            <div class="p-2 italic text-gray-700 text-xl font-semibold text-center">
                Sprint Start: {{ Carbon\Carbon::parse($currentSprint->start)->toFormattedDayDateString() }}
            </div>
            <div class="p-2 italic text-gray-700 text-xl font-semibold text-center">
                Sprint End: {{ Carbon\Carbon::parse($currentSprint->end)->toFormattedDayDateString() }}
            </div>
        </div>
    </div>
    @if ($isAssignmentFormOpened)
        <div>
            <livewire:multiple-choice-input :searchModel="$searchModel" :searchFields="$searchFields" :fieldsToShow="$fieldsToShow" :selectedIds="$selectedIds" />
            <x-ValidationErrors />
        </div>
    @endif
    @if ($sprints->count() == 0)
        <p class="text-center text-2xl mt-[10%] text-gray-800 font-semibold italic tracking-widest">There are no active
            tasks</p>
    @else
        <div class="bg-gray-100 text-gray-800 rounded-lg p-8 m-4">
            <h1 class="text-4xl font-extrabold mb-8 text-center">Task Management</h1>
            @foreach ($currentSprint->tasks as $task)
                <div class="bg-teal-500 text-white rounded-md p-6 mb-8 shadow-lg">
                    @if (!$isAssignmentFormOpened)
                        <button wire:click="toggleAssignmentForm({{ $task->id }})"
                            class="hover:bg-green-800 duration-300 bg-green-600 rounded-full p-2"><svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class=" w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                        </button>
                    @endif
                    <div class="mb-4">
                        <h2 class="text-2xl font-semibold mb-2">Task</h2>
                        <p class="text-lg">{{ $task->task }}</p>
                    </div>
                    <div>
                        <a href="/projects/{{ $task->projectId }}/view/task/{{ $task->id }}"
                            class="bg-purple-600 p-2 tracking-widest w-20 rounded-md hover:bg-purple-800 duration-300">View</a>
                    </div>
                </div>
            @endforeach
    @endif
</div>
</div>
