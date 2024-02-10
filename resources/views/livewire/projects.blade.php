<div>
    <x-ProjectsNavbar />
    <div class="w-full mt-[4%]">
        <p class="text-center text-white font-bold text-8xl">All Projects</p>
    </div>
    <div class="grid grid-cols-2 p-5 mt-[5%] gap-2 place-items-center">
        @foreach ($projects as $project)
            <div class="bg-white text-xl rounded-md w-[40%] text-gray-200">
                <div class="w-full font-semibold text-gray-200 p-4 bg-green-800"><span
                        class="font-bold text-gray-300">Project: </span>{{ $project->name }}</div>
                <div class="w-full p-4 h-fit text-gray-600 "><span class="font-bold text-gray-500">Description:
                    </span>{{ $project->description }}</div>
                {{-- @if ($project->role == 'Project Owner') --}}
                {{-- @endif --}}
                <div class="flex flex-row w-fit mx-auto mt-[15%]">
                        <div><a href="/projects/{{$project->id}}" wire:navigate class="cursor-pointer bg-blue-700 font-semibold mb-[2%] rounded-md  w-full p-2">View
                                Details</a></div>
                </div>
                {{-- @if ($isRequestWindowOpened)
                    <hr class="border-gray-500 w-[95%] mx-auto text-center rounded-lg border-1">
                    <form wire:submit="createNewRequest({{ $project->id }})" class="w-full p-2">
                        <div class="w-full flex flex-col">
                            <p class="text-black w-full p-2 text-center font-semibold text-2xl italic">New Request</p>
                            <textarea wire:model.live="newRequest" class="p-2 border-2 border-gray-200 bg-gray-100 w-full text-gray-900"
                                placeholder="Some new request..."></textarea>
                            <button type="submit"
                                class="bg-blue-600 p-2 mt-[2%] font-semibold hover:bg-blue-900 duration-300 rounded-md">Confirm</button>
                        </div>
                    </form>
                @endif --}}
            </div>
        @endforeach
    </div>
</div>
