<?php

namespace App\Mappers;

use App\Domain\Product\ValueObjects\Name;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Sale\ProductSale;
use App\Models\Product as ProductModel;
use Illuminate\Support\Arr;

class ProductSaleMapper
{
    public static function fromEloquent(ProductModel $product, int $amount): ProductSale
    {
        $product = new ProductSale($product->id, new Name($product->name), new Price($product->price), $amount);
        return $product;
    }

    public static function fromArray(array $product): ProductSale
    {
        $productModel = new ProductModel(Arr::only($product, ['name', 'price']));
        $productModel->id = $product['product_id'] ?? null;
        return self::fromEloquent($productModel, $product['amount']);
    }
}
