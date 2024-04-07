<?php

namespace App\Services\ViewDetails;


class CommentsViewData implements ViewDetailsComponentInterface
{
    public function __construct(protected string $caption, protected string $commentableType, protected int $commentableId)
    {
    }

    public function isBladeView(): bool
    {
        return true;
    }
    public function getViewName(): string
    {
        return 'ViewData.comments';
    }

    public function getData(): mixed
    {
        return ['caption' => $this->caption, 'commentableType' => $this->commentableType, 'commentableId' =>  $this->commentableId];
    }

    public function getSectionName(): string
    {
        return $this->caption;
    }
}