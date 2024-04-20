<?php

namespace App\Services\TableViews;

use App\Services\HyperLinkCreator;
use App\Services\Filter;
use App\Services\TableViews\Buttons\FilterButton;

abstract class TableView implements TableInterface
{
    protected array $links = [];

    protected static array $toolbarButtons = [];

    protected array $hiddenFields = [];

    public function __construct(public ?Filter $filter=null) {
        if(!is_null($filter)){
            static::$toolbarButtons[] = new FilterButton();
        }
    }

    abstract public static function getToolbarButtons();

    abstract public function render(string $keyword);

    public function addLink(string $fieldName, HyperLinkCreator $hyperLinkCreator)
    {
        $this->links[$fieldName] = $hyperLinkCreator;
        return $this;
    }

    public function isNotHidden($fieldName){
        return !in_array($fieldName, $this->hiddenFields);
    }

    public function fieldHasLink($fieldName)
    {
        return array_key_exists($fieldName, $this->links);
    }

    public function getLinkMaker($fieldName)
    {
        return $this->links[$fieldName];
    }
}
