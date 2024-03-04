<?php

declare(strict_types=1);

namespace App\Domain\Product\Entities;

use App\Domain\Entity;
use App\Domain\Product\ValueObjects\Name;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Description;

/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Schema para representar um produto",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do produto"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do produto"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Preço do produto"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descrição do produto"
 *     )
 * )
 */
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
