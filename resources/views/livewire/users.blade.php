<div>
    <x-UsersNavbar />
    <div class="w-full mt-[4%]">
        <p class="text-center text-white font-bold text-8xl">All Users</p>
    </div>
    <div class="grid grid-cols-1 p-5 mt-[5%] grid-rows- gap-2 place-items-center">
        @foreach ($users as $user)
            <div class="bg-[#669895] text-xl p-4 rounded-md w-[40%] text-gray-200">
                <div class="col-span-1"><span class="font-bold text-gray-200">Name: </span>{{ $user->name }}</div>
                <div class="col-span-1"><span class="font-bold text-gray-200">Email: </span>{{ $user->email }}</div>
                @if($user->role == 'Project Owner')
                <div class="col-span-1 "><span class="font-bold text-gray-200">Role: </span> <span class="italic font-semibold text-xl text-yellow-400">{{ $user->role }}</span></div>
                @endif
                @if($user->role == 'Development Team')
                <div class="col-span-1 "><span class="font-bold text-gray-200">Role: </span> <span class="italic font-semibold text-xl text-blue-700">{{ $user->role }}</span></div>
                @endif
                @if($user->role == 'Scrum Master')
                <div class="col-span-1 "><span class="font-bold text-gray-200">Role: </span> <span class="italic font-semibold text-xl text-green-400">{{ $user->role }}</span></div>
                @endif
                <div class="col-span-1 "><span class="font-bold text-gray-200">Registration Date: </span> <span class="italic text-lg text-gray-300">{{ $user->created_at }}</span></div>
            </div>
        @endforeach
    </div>
</div>
