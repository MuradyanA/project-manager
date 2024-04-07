<?php

namespace App\Services\ViewDetails;

interface ViewDetailsComponentInterface{
    public function isBladeView() : bool;

    public function getViewName() : string;

    public function getData() : mixed;

    public function getSectionName() : string;
}