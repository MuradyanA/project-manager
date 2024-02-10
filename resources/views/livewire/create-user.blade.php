<div>
    <x-UsersNavbar />

    <div class="w-full h-fit grid grid-cols-5 mt-[10%]">
        <div class="col-span-3 w-[60%] flex justify-end">
            <p class="text-center text-white font-bold text-8xl tracking-widest">Create <br> New <br> User</p>
        </div>
        <div class="col-span-2 w-[50%] shadow-2xl">
            <div class="bg-gray-400 w-full h-16">
                <p class="text-white font-bold text-xl text-center pt-[4%]">User Info</p>
            </div>
            <div class="w-full h-fit bg-gray-100 p-5 ">
                <form wire:submit="createUser" class="flex flex-col">
                    <label class="text-gray-600 text-lg font-semibold" for="">Full Name</label>
                    <input wire:model.live.blur="name"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="text" placeholder="John Doe">
                    <label class="text-gray-600 text-lg font-semibold" for="">Email</label>
                    <input wire:model.live.blur="email"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="email" placeholder="myemail@example.com">
                        {{-- <label class="text-gray-600 text-lg font-semibold" for="">Password</label>
                        <input wire:model.live="password"
                            class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                            type="text" placeholder="Generate Random Password"> --}}
                    <label class="text-gray-600 text-lg font-semibold" for="">Role</label>
                    <select wire:model.live="role" class="bg-gray-200 font-semibold text-gray-600 border-2 border-gray-300 p-2 rounded-md">
                        <option selected>Select a Role</option>
                        <option value="Project Owner">Project Owner</option>
                        <option value="Scrum Master">Scrum Master</option>
                        <option value="Scrum Master">Development Team</option>
                    </select>
                    <label class="text-gray-600 text-lg font-semibold" for="">Customer ID</label>
                    <input wire:model.live.blur="customerId"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="number" placeholder="1">
                    @error('fullName')
                        <span class="block text-red-700 text-end">Full Name: {{ $message }}</span>
                    @enderror
                    @error('email')
                        <span class="block text-red-700 text-end">Email: {{ $message }}</span>
                    @enderror
                    @error('password')
                        <span class="block text-red-700 text-end">Password: {{ $message }}</span>
                    @enderror
                    @error('role')
                        <span class="block text-red-700 text-end">Role: {{ $message }}</span>
                    @enderror
                    @error('customerId')
                        <span class="block text-red-700 text-end">Customer Id: {{ $message }}</span>
                    @enderror
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-[#0077c5] hover:bg-[#004f82] duration-300 text-center w-auto font-bold rounded-md text-lg p-2 mt-[10%] text-gray-100 ">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
