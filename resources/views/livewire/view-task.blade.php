<div class="mb-[2%] mx-auto w-[98%] bg-gray-100 h-screen items-center">
    <div class="w-full bg-white p-8 rounded-lg shadow-lg h-fit mt-[2%]">
        <h1 class="text-center text-5xl font-bold mb-4 text-blue-500">Project - {{ $currentProject->name }}</h1>
        <p class="text-gray-700 text-center text-2xl"><span class="font-semibold"> Task - </span>
            {{ $currentTask->task }}
        </p>
    </div>

    <livewire:Comment :commentableType="$commentableType" :commentableId="$currentTask->id" />
</div>
