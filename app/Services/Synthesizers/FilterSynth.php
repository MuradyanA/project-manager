<?php

namespace App\Services\Synthesizers;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use App\Services\Filter;

class FilterSynth extends Synth
{
    public static $key = 'filter';
 
    public static function match($target)
    {
        return $target instanceof Filter;
    }
 
    public function dehydrate($target)
    {
        return [$target->dehydrate(), []];
    }
 
    public function hydrate($value)
    {
        $instance = new Filter;
 
        $instance->hydrate($value); 
        return $instance;
    }
 
    public function get(&$target, $key) 
    {
        return $target->{$key};
    }
 
    public function set(&$target, $key, $value)
    {
        $target->{$key} = $value;
    }
}