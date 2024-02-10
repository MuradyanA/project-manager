<div>
    <x-ProjectsNavbar />

    <div class="w-full h-fit grid grid-cols-5 mt-[10%]">
        <div class="col-span-3 w-[60%] flex justify-end">
            <p class="text-center text-white font-bold text-8xl tracking-widest">Create <br> New <br> Project</p>
        </div>
        <div class="col-span-2 w-[50%] shadow-2xl">
            <div class="bg-gray-400 w-full h-16">
                <p class="text-white font-bold text-xl text-center pt-[4%]">Project Info</p>
            </div>
            <div class="w-full h-fit bg-gray-100 p-5 ">
                <form wire:submit="createProject" class="flex flex-col">
                    <label class="text-gray-600 text-lg font-semibold" for="">Project Name</label>
                    <input wire:model.live.blur="projectName"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="text" placeholder="Mail Service">
                    <label class="text-gray-600 text-lg font-semibold" for="">Description</label>
                    <textarea wire:model.live.blur="description"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="text" placeholder="Some description..."></textarea>
                    <label class="text-gray-600 text-lg font-semibold" for="">Customer ID</label>
                    <input wire:model.live.blur="customerId"
                        class="bg-gray-200 font-semibold text-gray-700 border-2 border-gray-300 p-2 rounded-md"
                        type="number" placeholder="10">
                    @error('projectName')
                        <span class="block text-red-700 text-end">Project Name: {{ $message }}</span>
                    @enderror
                    @error('description')
                        <span class="block text-red-700 text-end">Description: {{ $message }}</span>
                    @enderror
                    @error('customerId')
                        <span class="block text-red-700 text-end">Customer ID: {{ $message }}</span>
                    @enderror
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-[#0077c5] hover:bg-[#004f82] duration-300 text-center w-[30%] font-bold rounded-md text-lg p-2 mt-[10%] text-gray-100 ">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
