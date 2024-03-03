<?php

declare(strict_types=1);

namespace App\Domain\Sale\ValueObjects;

use App\Domain\Exceptions\MinimumValueException;
use App\Domain\Exceptions\RequiredException;
use App\Domain\ValueObject;

final class Amount extends ValueObject
{
    public float $amount;

    public function __construct(?float $amount)
    {
        if (!$amount) {
            throw new RequiredException('total');
        }
        if ($amount < 0) {
            throw new MinimumValueException('total', '0');
        }

        $this->amount = $amount;
    }

    public function __toString(): string
    {
        return $this->amount . '';
    }

    public function jsonSerialize(): string
    {
        return $this->amount . '';
    }
}
