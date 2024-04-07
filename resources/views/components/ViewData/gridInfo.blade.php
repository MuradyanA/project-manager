<div class="flex flex-wrap gap-4">
    @for ($i = 0; $i < count($fieldNames); ++$i)
        <div class=" border-2 border-gray-50 rounded-md mt-2 px-2 p-1">
            @if (is_a($fieldValues[$i], App\Services\HyperLinkCreator::class))
                <span class="font-bold"> {{ $fieldNames[$i] }}: </span> {!! $fieldValues[$i]->make() !!}
            @elseif (is_a($fieldValues[$i], App\Services\ViewDetails\ButtonCreator::class))
                @php
                    extract($fieldValues[$i]->getData());
                @endphp
                <x-forms.details-view-button :caption="$caption" :actionName="$actionName" :svg="$svg" :confirmationMessage="$confirmationMessage"
                    :class="$class" />
            @else
                <span class="font-bold"> {{ $fieldNames[$i] }}: </span>{{ $fieldValues[$i] }}
            @endif
        </div>
    @endfor
</div>
