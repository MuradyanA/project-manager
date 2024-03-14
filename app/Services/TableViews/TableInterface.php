<?php

namespace App\Services\TableViews;

interface TableInterface{
    public static function getToolbarButtons();
    public function render(string $keyword);
}
