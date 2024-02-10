<div class="w-full flex justify-center items-center min-h-screen">
    <div class="w-[20%] bg-[#111827] p-8 rounded-md">
        <form wire:submit="submit">
            <div class="flex flex-col">
                <!-- {{-- <label class="text-lg text-gray-200">Email</label>
                <input wire:model.live.blur="email" name="email"
                    class="bg-[#0a101e] h-7 text-gray-400 font-bold p-4 border-2 
                rounded-md border-[#2b3269]" /> --}} -->
                <x-forms.input wire:model.live.blur="email" name="email" caption="Email"/>

                <x-forms.input wire:model.live.blur="password" name="password" caption="Password"/>
                {{-- <label class="text-lg text-white text-gray-200">Password</label>
                <input wire:model.live.blur="password" type="password"
                    class="bg-[#0a101e] text-gray-400 h-7 p-4 border-2 rounded-md border-[#2b3269]" /> --}}
                <div class="mt-2">
                    <input wire:model.live="rememberPassword" type="checkbox">
                    <span class="text-gray-300 text-lg">Remember password</span>
                </div>
                <div class="flex justify-center items-center mt-[6%]">
                    <button type="submit"
                        class="bg-blue-700 w-[45%] p-1 text-gray-200 text-lg rounded-md hover:bg-blue-900 hover:text-gray-100 duration-200">Sign
                        In</button>
                </div>
            </div>
        </form>
    </div>
</div>
