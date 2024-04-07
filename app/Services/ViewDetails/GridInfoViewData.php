<?php

namespace App\Services\ViewDetails;

class GridInfoViewData implements ViewDetailsComponentInterface
{
    /**
     * @param array $data Data must be an associative array where the values may be string
     * or instances of HyperLinkCreator or ButtonCreator classes 
     */
    public function __construct(protected array $data, protected string $caption, public array $substitutedNames = [])
    {
    }

    public function isBladeView(): bool
    {
        return true;
    }
    public function getViewName(): string
    {
        return 'ViewData.gridInfo';
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getSectionName(): string
    {
        return $this->caption;
    }
}
