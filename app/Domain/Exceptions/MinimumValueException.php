<?php

namespace App\Domain\Exceptions;

final class MinimumValueException extends \DomainException
{
    public function __construct($fieldName, $value)
    {
        parent::__construct(__("The minimum value for '$fieldName' is '$value"));
    }
}
