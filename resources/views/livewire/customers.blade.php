<div>
    <livewire:notification />
    <div class="w-full h-fit flex justify-center mt-[5%]">
        <div class="w-fit shadow-2xl">
            <div class="w-full h-fit bg-gray-100 ">
                <x-forms.form :builder="$this->createBuilder()" />
            </div>
        </div>
    </div>
    <livewire:table-view :key="$tableKey" tableClass="{{ App\Services\TableViews\CustomersTable::class }}" />
</div>
