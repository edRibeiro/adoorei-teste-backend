<?php

namespace App\Mappers;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\Description;
use App\Domain\Product\ValueObjects\Name;
use App\Domain\Product\ValueObjects\Price;
use App\Dtos\ProductDto;
use App\Models\Product as ProductModel;

class ProductMapper
{
    public static function toEloquent(ProductDto $product): ProductModel
    {
        $productModel = new ProductModel();
        if ($product->product_id) {
            $productModel = ProductModel::query()->findOrFail($product->product_id);
        }
        $productModel->name = $product->name;
        $productModel->price = $product->price;
        $productModel->description = $product->description;
        return $productModel;
    }

    public static function fromEloquent(ProductModel $departmentEloquent): Product
    {
        return new Product(
            $departmentEloquent->id,
            new Name($departmentEloquent->name),
            new Price($departmentEloquent->price),
            new Description($departmentEloquent->description)
        );
    }
}
