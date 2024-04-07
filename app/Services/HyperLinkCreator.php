<?php

namespace App\Services;

class HyperLinkCreator
{
    protected $params = [];
    protected $title = "";

    public function __construct(private string $routeName)
    {
    }

    public function setParams(string $title, array $params)
    {
        $this->params = $params;
        $this->title = $title;
        return $this;
    }

    public function make()
    {
        $route = route($this->routeName, $this->params);
        $title = htmlspecialchars($this->title);
        return <<<EOC
               <a href="$route" class="underline underline-offset-1" wire:navigate> $title </a>
               EOC;
    }
}
