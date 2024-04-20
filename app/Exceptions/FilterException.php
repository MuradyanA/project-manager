<?php

namespace App\Exceptions;

use Exception;

class FilterException extends Exception
{
    public static function FilterError(string $e): static
    {
        return new static($e);
    }
}
