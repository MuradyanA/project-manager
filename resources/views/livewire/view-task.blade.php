<div class="mb-[2%] mx-auto w-[98%] bg-gray-100 h-screen items-center">
    <div class="w-full bg-white p-8 rounded-lg shadow-lg h-fit mt-[2%]">
        <div class="flex justify-between">
            <button wire:click="changeTaskStage('moveBack')"
                class="bg-orange-500 rounded-sm p-2 text-white mt-2 px-[2%] font-semibold text-lg">Previous
                Stage</button>
            <button wire:click="changeTaskStage('moveForward')"
                class="bg-green-500 rounded-sm p-2 text-white mt-2 px-[2%] font-semibold text-lg">Next Stage</button>
        </div>
        <h1 class="text-center text-5xl font-bold mb-4 text-blue-500">Project - {{ $currentProject->name }}</h1>
        <div class="flex items-center justify-center">
            @if (!$isEditTaskFormOpened)
                <button class="bg-green-600 p-1 rounded-md mr-[1%] hover:bg-green-800 duration-300"
                    wire:click="editTask({{ $currentTask->id }}, '{{ $currentTask->task }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-gray-200">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </button>
            @endif
            <p class="text-gray-700 text-center text-2xl"><span class="font-semibold">Task -</span>
                {{ $currentTask->task }}</p>
        </div>
        <div class="flex justify-center">
            <button wire:click="changeTaskStage('toggleActiveStatus')"
                class="rounded-sm p-2 text-gray-200 mt-2 px-[2%] font-semibold text-lg 
           {{ $currentTask->status == 'Rejected' ? 'bg-green-700' : 'bg-red-700' }}">
                {{ $currentTask->status == 'Rejected' ? 'Reactivate' : 'Reject' }}
            </button>

        </div>
        <div class="flex flex-col items-center justify-center">
            <p class="text-gray-700 text-center text-xl py-[1%] font-semibold italic">Status: {{ $currentTask->status }}
            </p>
            @if ($isEditTaskFormOpened)
                <textarea wire:model="newTaskDescription" class="p-2 h-32 w-[30%] bg-gray-100 border-2 border-black" type="text"></textarea>
            @endif
            <x-ValidationErrors />
        </div>
        <div class="flex justify-center">
            <div class="p-2 italic text-gray-700 text-xl font-semibold text-center">
                @if ($isEditTaskFormOpened)
                    Task Start: <input wire:model.change="taskStart" class="border-2 border-gray-800 rounded-md p-1"
                        type="date" value={{ $currentTask->start }} />
                @else
                    Task Start: {{ Carbon\Carbon::parse($currentTask->start)->toFormattedDayDateString() }}
                @endif
            </div>
            <div class="p-2 italic text-gray-700 text-xl font-semibold text-center">
                @if ($isEditTaskFormOpened)
                    Task End: <input wire:model.change="taskEnd" class="border-2 border-gray-800 rounded-md p-1"
                        type="date" value={{ $currentTask->end }} />
                @else
                    Task End: {{ Carbon\Carbon::parse($currentTask->end)->toFormattedDayDateString() }}
                @endif
            </div>
        </div>
        <div class="flex justify-center">
            @if ($isEditTaskFormOpened)
                <button wire:click="saveChangedTask({{ $currentTask->id }})"
                    class="bg-blue-500 rounded-sm p-2 text-gray-200 mt-2 px-[2%] font-semibold text-lg">Save</button>
            @endif
        </div>
        <div class="flex justify-center">
            <p class="text-gray-700 font-semibold text-lg">Users Assigned To Task</p>
        </div>
        <div>
            <div class="flex flex-row">
                @foreach ($assignedUsers as $assignedUser)
                    <div class="w-fit p-2 text-md bg-gray-100 border-2 border-gray-300 rounded-sm">
                        <p class="italic">{{ $assignedUser['name'] }} | {{ $assignedUser['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <livewire:Comment :commentableType="$commentableType" :commentableId="$currentTask->id" :currentTask="$currentTask" :comments="$comments" :likesDislikes="$likesDislikes"
        :openedReplyForms="$openedReplyForms" :showRepliedComments="$showRepliedComments" :repliedRepliedComments="$repliedRepliedComments" :isRepliedCommentsShown="$isRepliedCommentsShown" :repliedComment="$repliedComment" :currentProject="$currentProject"
        :openedReplyReplingForms="$openedReplyReplingForms" />
</div>
