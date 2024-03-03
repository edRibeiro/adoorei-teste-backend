<?php

namespace App\Dtos;

use App\Domain\Exceptions\MinimumValueException;
use App\Domain\Exceptions\RequiredException;
use App\Domain\Sale\ValueObjects\Products;
use App\Mappers\ProductSaleMapper;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleDto
{
    public float $amount;
    public Products $products;
    public ?int $sale_id;

    public function __construct(float $amount, Products $products,  ?int $sale_id = null)
    {
        $this->amount = $amount;
        $this->products = $products;
        $this->sale_id = $sale_id;
    }

    public static function fromEloquent(Sale $sale): self
    {
        $sale->load('products');
        $products = new Products([]);
        foreach ($sale->products as $product) {
            $products->add(ProductSaleMapper::fromEloquent($product, $product->pivot->amount));
        }
        return new self(
            floatval($sale->amount),
            $products,
            $sale->id
        );
    }

    public static function fromRequest(Request $request, ?int $sale_id = null): self
    {
        $products = new Products(
            array_map(function ($product) {
                return ProductSaleMapper::fromArray($product);
            }, self::verifyProductsFromRequest($request))
        );
        $amount = array_reduce($products->getProducts(), fn ($total, $product) => ($total + $product->getTotal()), 0);
        return new self(
            amount: $amount,
            products: $products,
            sale_id: $sale_id,
        );
    }

    public function toArray(): array
    {
        return [
            'sale_id' => $this->sale_id,
            'amount' => $this->amount,
            'products' => $this->products,
        ];
    }

    public static function verifyProductsFromRequest(Request $request): array
    {
        $productsRequest = $request->input('products');
        if (!$productsRequest) {
            throw new RequiredException('products');
        }
        if (count($productsRequest) < 1) {
            throw new MinimumValueException('products', '1');
        }
        return $productsRequest;
    }

    public function getProducts(): array
    {
        return $this->products->getProducts();
    }
}
