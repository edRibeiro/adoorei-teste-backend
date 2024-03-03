<?php

declare(strict_types=1);

namespace App\Domain\Product\Entities;

use App\Domain\Entity;
use App\Domain\Product\ValueObjects\Name;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Description;

class Product extends Entity
{
    public ?int $product_id;
    public  Name $name;
    public  Price $price;
    public  Description $description;

    public function __construct(
        ?int $product_id,
        Name $name,
        Price $price,
        Description $description
    ) {
        $this->product_id = $product_id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
        ];
    }
}
