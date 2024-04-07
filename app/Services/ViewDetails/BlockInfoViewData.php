<?php

namespace App\Services\ViewDetails;


class BlockInfoViewData implements ViewDetailsComponentInterface
{
    public function __construct(protected string $caption, protected string $fieldName, protected string $text)
    {
    }

    public function isBladeView(): bool
    {
        return true;
    }
    public function getViewName(): string
    {
        return 'ViewData.blockInfo';
    }

    public function getData(): mixed
    {
        return ['caption' => $this->caption, 'fieldName' => $this->fieldName, 'text' =>  $this->text];
    }

    public function getSectionName(): string
    {
        return $this->caption;
    }
}
