<?php

namespace App\View\Components\ViewData;

use App\Services\ViewDetails\ViewDetailsComponentInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GridInfo extends Component
{

    public function __construct(public ViewDetailsComponentInterface $section)
    {
        // dd($data);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ViewData.gridInfo', ['fieldNames' => $this->getFieldNames(), 'fieldValues' => array_values($this->section->getData())]);
    }

    public function getFieldNames(){

        return array_map(function($fieldName){
            if(array_key_exists($fieldName, $this->section->substitutedNames)){
                return ucfirst($this->section->substitutedNames[$fieldName]);
            }
            return ucfirst($fieldName);
        }, array_keys($this->section->getData()));
    }
}
