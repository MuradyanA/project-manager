<?php

namespace App\Services\ViewDetails;

use App\Services\Exceptions\DetailsViewException;
use App\Services\ViewDetails\ViewDetailsComponentInterface;

class DetailsViewBuilder
{
    protected array $pages = [];

    public function addPage($pageCaption)
    {
        $this->pages[str_replace(' ', '_', $pageCaption)] = [];
        return $this;
    }

    public function addSection(string $page, ViewDetailsComponentInterface $viewDetails)
    {
        if (!array_key_exists($this->makePageName($page), $this->pages)) {
            throw DetailsViewException::DetailsViewError("The provided page name doesn't exists");
        }
        $this->pages[$this->makePageName($page)][] = $viewDetails;
        return $this;
    }

    protected function makePageName($pageCaption)
    {
        return str_replace(' ', '_', $pageCaption);
    }

    public function getPageNames()
    {
        return array_map(fn ($header) => ucwords(str_replace('_', ' ', $header)), array_keys($this->pages));
    }

    public function getPages()
    {
        return $this->pages;
    }

}
