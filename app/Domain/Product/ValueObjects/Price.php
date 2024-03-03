<?php

declare(strict_types=1);

namespace App\Domain\Product\ValueObjects;

use App\Domain\Exceptions\MinimumValueException;
use App\Domain\Exceptions\RequiredException;
use App\Domain\ValueObject;

final class Price extends ValueObject
{
    public float $price;

    public function __construct(?float $price)
    {
        if (!$price) {
            throw new RequiredException('preço');
        }
        if ($price < 0) {
            throw new MinimumValueException('preço', '0');
        }

        $this->price = $price;
    }

    public function __toString(): string
    {
        return $this->price . '';
    }

    public function jsonSerialize(): string
    {
        return $this->price . '';
    }
}
