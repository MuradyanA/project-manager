<div
    {{ $attributes->class(['fixed rounded-sm drop-shadow-2xl h-fit bg-gray-100 top-[35%] bottom-[65%] left-1/2 -translate-x-1/2' => $builder->positionType == 'fixed']) }}>
    @if ($builder->formTitle)
        <div>
            <h3 class="p-3 text-lg bg-gray-300 text-center text-gray-600 font-semibold">{{ $builder->formTitle }}</h3>
        </div>
    @endif
    <div>
        <form wire:submit="{{ $builder->onSubmitAction }}" class="flex flex-col bg-gray-100 p-3">
            @if ($builder->isSectionExists())
            @else
                @php
                    $inputs = $builder->getInputs();
                @endphp
                @foreach ($inputs as $input)
                    <x-dynamic-component component="{{ $input->getViewName() }}" :builder="$builder" :name="$input->name" />
                @endforeach
            @endif
            <x-ValidationErrors />
            <hr class="bg-gray-700 drop-shadow-2xl">
            <div class="w-full flex gap-1 justify-end mt-5">
                @foreach ($builder->getButtons() as $button)
                    <x-dynamic-component component="{{ $button->getViewName() }}" :builder="$builder" :name="$button->name" />
                @endforeach
                <x-BlueSubmitButton />
            </div>
        </form>
    </div>
</div>
