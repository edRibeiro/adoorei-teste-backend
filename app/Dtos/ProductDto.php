<?php

namespace App\Dtos;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductDto
{
    public ?int $product_id;
    public string $name;
    public float $price;
    public ?string $description;

    public function __construct(string $name, float $price, string $description, ?int $product_id = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->product_id = $product_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['product_id'],
            $data['name'],
            $data['price'],
            $data['description']
        );
    }

    public static function fromEloquent(Product $product): self
    {
        return new self(
            $product->name,
            $product->price,
            $product->description,
            $product->id
        );
    }

    public static function fromRequest(Request $request, ?int $product_id = null): self
    {
        return new self(
            name: $request->input('name'),
            price: $request->input('price'),
            description: $request->input('description'),
            product_id: $product_id,
        );
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
