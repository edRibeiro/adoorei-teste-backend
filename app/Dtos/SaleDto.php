<?php

namespace App\Dtos;

use App\Mappers\ProductSaleMapper;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleDto
{
    public float $amount;
    public array $products;
    public ?int $sale_id;

    public function __construct(float $amount, array $products = [],  ?int $sale_id = null)
    {
        $this->amount = $amount;
        $this->products = $products;
        $this->sale_id = $sale_id;
    }

    public static function fromEloquent(Sale $sale): self
    {
        $sale->load('products');
        $products = [];
        foreach ($sale->products as $product) {
            $products[] = ProductSaleMapper::fromEloquent($product, $product->pivot->amount);
        }
        return new self(
            floatval($sale->amount),
            $products,
            $sale->id
        );
    }

    public static function fromRequest(Request $request, ?int $sale_id = null): self
    {
        return new self(
            amount: $request->input('amount'),
            products: $request->input('products'),
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
}
