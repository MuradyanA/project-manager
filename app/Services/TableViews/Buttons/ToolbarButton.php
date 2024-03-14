<?php

namespace App\Services\TableViews\Buttons;

use App\Services\TableViews\ToolbarButtonInterface;

class ToolbarButton implements ToolbarButtonInterface
{

    protected string $action;
    protected string $confirmationMessage = "";
    protected string $icon;
    protected string $title = '';


    public function render()
    {

        // $action = static::$action;
        // $icon = static::$icon;
        $actionName = "'" . $this->action . "'";
        $confirmation = $this->confirmationMessage != "" ? 'wire:confirm="' . $this->confirmationMessage . '"' : '';
        return <<<Button
        <button title="$this->title" class="text-gray-700 border-2 shadow-lg bg-gray-200 p-2 border-gray-400"
        wire:click="action($actionName)" $confirmation >$this->icon</button>
        Button;
    }
}
