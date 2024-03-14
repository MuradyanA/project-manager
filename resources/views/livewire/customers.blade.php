<div>
    <livewire:notification />
    <div class="w-full h-fit grid grid-cols-5 mt-[10%]">
        <div class="col-span-3 w-[60%] flex justify-end">
            @if ($customerForm->id)
                <p class="text-center text-white font-bold text-6xl p-4">Update <br> Customer <br> Info</p>
            @else
                <p class="text-center text-white font-bold text-6xl p-4 ">Create <br> New <br> Customer</p>
            @endif
        </div>
        <div class="col-span-2 w-[50%] shadow-2xl">
            <div class="bg-gray-400 w-full h-16">
                <p class="text-white font-bold text-xl text-center pt-[4%]">Customer Info</p>
            </div>
            <div class="w-full h-fit bg-gray-100 p-5 ">
                <form wire:submit="saveCustomer()" class="flex flex-col">
                    @if ($customerForm->id)
                        <x-forms.input disabled wire:model="customerForm.id" name="customerForm.id" caption="ID" />
                    @endif
                    <x-forms.input wire:model.blur="customerForm.fullName" name="customerForm.fullName"
                        caption="Full Name" />
                    <x-forms.input wire:model.blur="customerForm.email" name="customerForm.email" caption="Email" />
                    <x-forms.input wire:model.blur="customerForm.phoneNumber" name="customerForm.phoneNumber"
                        caption="Phone Number" />
                    <div class="flex justify-center">
                        <x-BlueSubmitButton />
                    </div>
                    <x-ValidationErrors />
                </form>
            </div>
        </div>
    </div>
    <livewire:table-view :key="$tableKey" tableClass="{{ App\Services\TableViews\CustomersTable::class }}" />
</div>
