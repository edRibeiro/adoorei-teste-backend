<?php

namespace App\Domain\Exceptions;

class ProductNotExistsException extends \DomainException
{
    public function __construct($fieldName)
    {
        parent::__construct(trans('validation.exists', ['attribute' => $fieldName]));
    }
}
