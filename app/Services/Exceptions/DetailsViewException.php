<?php

namespace App\Services\Exceptions;


class DetailsViewException extends \Exception
{
    public static function DetailsViewError(string $e): static
    {
        return new static($e);
    }
}
