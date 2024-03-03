<?php

namespace App\Dtos;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductSaleDto
{
    public ?int $product_id;
    public string $name;
    public float $price;
    public ?int $amount;

    public function __construct(string $name, float $price, int $amount, ?int $product_id = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
        $this->product_id = $product_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['price'],
            $data['amount'],
            isset($data['product_id']) ? $data['product_id'] : null
        );
    }

    public static function fromEloquent(Product $product): self
    {
        return new self(
            $product->name,
            $product->price,
            $product->amount,
            $product->id
        );
    }

    public static function fromRequest(Request $request, ?int $product_id = null): self
    {
        return new self(
            name: $request->input('name'),
            price: $request->input('price'),
            amount: $request->input('amount'),
            product_id: $request->input('product_id') ?? $product_id,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $this->price,
            'amount' => $this->amount,
        ];
    }
}
