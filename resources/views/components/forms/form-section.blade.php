<div>
    @foreach ($builder->getInputs($name) as $input)
        {{-- <x-dynamic-component :component="$componentName" :builder="$builder" :name="$input->name" /> --}}
    @endforeach
</div>
