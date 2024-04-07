<?php

namespace App\View\Components\ViewData;

use App\Services\ViewDetails\DetailsViewBuilder;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DetailsView extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public DetailsViewBuilder $builder)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ViewData.details-view');
    }
}
