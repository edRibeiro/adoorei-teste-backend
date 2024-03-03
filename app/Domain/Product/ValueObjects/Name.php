<?php

declare(strict_types=1);

namespace App\Domain\Product\ValueObjects;

use App\Domain\Exceptions\RequiredException;
use App\Domain\ValueObject;

final class Name extends ValueObject
{
    public string $name;

    public function __construct(?string $name)
    {
        if (!$name) {
            throw new RequiredException('name');
        }

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
