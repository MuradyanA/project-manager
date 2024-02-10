<div>
    <div class="italic sticky top-20">
        <div class="w-full h-auto bg-[#367a76]  p-5 text-center">
            <a href="/projects/{{ $project->id }}"
                class="text-gray-100 text-2xl px-[2%] cursor-pointer duration-300">Project</a>
            <a href="/projects/{{ $project->id }}/sprints"
                class="text-gray-100 text-2xl px-[2%] cursor-pointer duration-300">Sprint</a>
        </div>
    </div>
    <div class="bg-[#a1d5cc] p-8">
        <h1 class="italic text-3xl font-bold text-center text-gray-800">Current Sprint {{ $sprintNumber }}</h1>
    </div>
    @if ($isAssignmentFormOpened)
        <div class="sticky top-[35%] z-10 mx-auto justify-center items-center w-[30%] bg-gray-200 rounded-md">
            <div class="bg-gray-200 text-start p-2"><button wire:click="closeAssignmentForm()"><svg
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="p-2 text-center w-full pb-[5%] italic text-xl font-semibold">Assignment</p>
            <form wire:submit="assignToTask" class="bg-white p-8 rounded-md shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Assign Users to Task</h2>

                @foreach ($users as $user)
                    <div class="flex items-center mb-4">
                        <input id="user_{{ $user->id }}" value="{{ $user->id }}" type="checkbox"
                            class="mr-3 appearance-none bg-gray-100 border-2 border-gray-300 rounded-md w-6 h-6 checked:bg-blue-500 checked:border-transparent"
                            wire:click="toggleUser({{ $user->id }})">
                        <label for="user_{{ $user->id }}" class="font-medium text-gray-700">
                            {{ $user->name }}
                            @if ($user->role == 'Development Team')
                                <span class="text-blue-500">(Development Team)</span>
                            @elseif($user->role == 'Scrum Master')
                                <span class="text-green-500">(Scrum Master)</span>
                            @endif
                        </label>
                    </div>
                @endforeach

                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 duration-300 text-white font-bold rounded-md text-lg px-6 py-3">Assign</button>
                </div>
            </form>
        </div>
    @endif
    @if($sprint->count()==0)
    <p class="text-center text-2xl mt-[10%] text-gray-800 font-semibold italic tracking-widest">There are no active tasks</p>
    @else
    <div class="bg-gray-100 text-gray-800 rounded-lg p-8 m-4">
        <h1 class="text-4xl font-extrabold mb-8 text-center">Task Management</h1>
        @foreach ($sprint as $sprnt)
            <div class="bg-teal-500 text-white rounded-md p-6 mb-8 shadow-lg">
                @if (!$isAssignmentFormOpened)
                    <button wire:click="toggleAssignmentForm({{ $sprnt->id }})"
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
                    <p class="text-lg">{{ $sprnt->task }}</p>
                </div>
                <div class="mb-4">
                    <h2 class="text-2xl font-semibold mb-2">Assigned Users:</h2>
                    @foreach ($users as $user)
                        <p
                            class="{{ $user->role !== 'Development Team' ? 'font-semibold text-lg text-green-700' : 'font-semibold text-lg text-blue-800' }}">
                            {{ $user->tasks->contains('id', $sprnt->id) ? $user->name : '' }}
                        </p>
                    @endforeach
                </div>
                <div>
                    <a href="/projects/{{ $sprnt->projectId }}/view/task/{{ $sprnt->id }}"
                        class="bg-purple-600 p-2 tracking-widest w-20 rounded-md hover:bg-purple-800 duration-300">View</a>
                </div>
            </div>
        @endforeach
        @endif
    </div>
</div>
