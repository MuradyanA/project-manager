<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\Url;
use App\Services\Exceptions\ServiceException;
use Livewire\Attributes\On;

trait WithViewDetailsView
{
    #[Url]
    public int $showDetails;

    #[Url]
    public string $pageName = "";

    #[On('ViewDetails')]
    public function showWindow($selectedItems, $tableClass)
    {
        $this->showDetails = $selectedItems[0];
    }

    public function closeDetailsWindow()
    {
        $this->showDetails = false;
    }

    public function switchPage($pageName)
    {
        $this->pageName = $pageName;
    }
}
