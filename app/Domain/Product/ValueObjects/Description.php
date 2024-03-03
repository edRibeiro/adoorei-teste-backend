<?php

declare(strict_types=1);

namespace App\Domain\Product\ValueObjects;

use App\Domain\Exceptions\RequiredException;
use App\Domain\ValueObject;

final class Description extends ValueObject
{
    public string $description;

    public function __construct(?string $description)
    {
        if (!$description) {
            throw new RequiredException('descrição');
        }

        $this->description = $description;
    }

    public function __toString(): string
    {
        return $this->description;
    }

    public function jsonSerialize(): string
    {
        return $this->description;
    }
}
