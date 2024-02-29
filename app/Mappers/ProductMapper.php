<?php

namespace App\Mappers;

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
}
