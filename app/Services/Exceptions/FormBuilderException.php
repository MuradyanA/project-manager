<?php

namespace App\Services\Exceptions;


class FormBuilderException extends \Exception
{
    public static function FormBuilderError(string $e): static
    {
        return new static($e);
    }
}
