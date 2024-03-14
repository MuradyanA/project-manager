<?php

namespace App\Services\Exceptions;


class ServiceException extends \Exception
{
    public static function ValidationError(string $e): static
    {
        return new static($e);
    }
}
