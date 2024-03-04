<?php

declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\AggregateRoot;

use App\Domain\Exceptions\MinimumValueException;
use App\Domain\Exceptions\RequiredException;
use App\Domain\Product\ValueObjects\Name;
use App\Domain\Product\ValueObjects\Price;

/**
 * @OA\Schema(
 *     schema="ProductSale",
 *     @OA\Property(
 *         property="product_id",
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
 *         property="amount",
 *         type="integer",
 *         description="Quantidade do produto"
 *     )
 * )
 */
final class ProductSale extends AggregateRoot
{
    public int $product_id;
    public Name $name;
    public Price $price;
    public int $amount;

    public function __construct(int $product_id, Name $name, Price $price, int $amount)
    {
        if (!$product_id) {
            throw new RequiredException('product_id');
        }
        if (!$amount) {
            throw new RequiredException('quantidade');
        }
        if ($amount < 0) {
            throw new MinimumValueException('quantidade', '1');
        }
        $this->product_id = $product_id;
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
    }


    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $this->price,
            'amount' => $this->amount
        ];
    }

    function getPrice(): float
    {
        return $this->price->getPrice();
    }

    function getTotal(): float
    {
        return self::getPrice() * floatval($this->amount);
    }
}
