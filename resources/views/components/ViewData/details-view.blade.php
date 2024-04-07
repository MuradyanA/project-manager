<div
    class='fixed z-10 rounded-sm drop-shadow-2xl h-fit bg-gray-100 top-[5%] bottom-[65%] left-1/2 -translate-x-1/2 w-[50%]'>
    <div class="flex gap-10 p-3">
        @foreach ($builder->getPageNames() as $page)
            <button @class([
                'border-b-2 border-gray-500 duration-100' =>
                    $this->pageName == ''
                        ? array_keys($builder->getPages())[0] == $page
                        : $this->pageName == $page,
            ]) wire:click="switchPage('{{ $page }}')"
                class="text-gray-600 font-bold">{{ $page }}</button>
        @endforeach
        <div class="w-full flex justify-end p-2">
            @php
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>';
            @endphp
            <x-forms.details-view-button class=" rounded-full !p-1 !bg-[#367a76]" :svg="$svg" :actionName="'closeDetailsWindow'" />
        </div>
    </div>
    <div>
        @foreach ($builder->getPages() as $page => $sections)
            @if ($this->pageName == '' ? array_keys($builder->getPages())[0] == $page : $this->pageName == $page)
                @if (count($sections) > 0)
                    @foreach ($sections as $section)
                        <x-ViewData.section :header="$section->getSectionName()">
                            @if ($section->isBladeView())
                                <x-dynamic-component :section="$section" :component="$section->getViewName()" />
                            @else
                                <livewire:dynamic-component :section="$section" :is="$section->getViewName()" />
                            @endif
                            </x-section>
                    @endforeach
                @endif
            @endif
        @endforeach

    </div>

</div>
