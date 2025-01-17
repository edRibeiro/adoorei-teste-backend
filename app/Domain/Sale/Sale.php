<?php

declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\AggregateRoot;
use App\Domain\Sale\ValueObjects\Products;
use App\Domain\Sale\ValueObjects\Amount;

/**
 * @OA\Schema(
 *     schema="Sale",
 *     @OA\Property(
 *         property="sale_id",
 *         type="integer",
 *         description="ID da venda"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         description="Valor total da venda"
 *     ),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ProductSale")
 *     )
 * )
 */
class Sale extends AggregateRoot
{
    public ?int $id;
    public Amount $amount;
    public Products $products;

    public function __construct(?int $id, Amount $amount, Products $products)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->products = $products;
    }

    public function addProduct(ProductSale $product): void
    {
        $this->products->add($product);
    }
    public function updateProduct(ProductSale $newProduct): void
    {
        $this->products->update($newProduct);
    }
    public function removeProduct(int $product_id): void
    {
        $this->products->remove($product_id);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'products' => $this->products->toArray()
        ];
    }

    public function getProducts(): array
    {
        return $this->products->getProducts();
    }
}
