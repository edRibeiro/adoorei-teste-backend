<?php

namespace App\UseCases\Sales\Commands;

use App\Domain\Exceptions\ProductNotExistsException;
use App\Dtos\SaleDto;
use App\Mappers\ProductSaleMapper;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\UseCases\CommandInterface;


class AddProductFromSaleCommand implements CommandInterface
{
    private SaleRepositoryInterface $saleRepository;
    private ProductRepositoryInterface $productRepository;
    private SaleDto $sale;

    public function __construct(SaleDto $sale)
    {
        $this->sale = $sale;
        $this->saleRepository = app()->make(SaleRepositoryInterface::class);
        $this->productRepository = app()->make(ProductRepositoryInterface::class);
    }

    public function execute(): mixed
    {
        foreach ($this->sale->getProducts() as $product) {
            try {
                $productModel = $this->productRepository->findById($product->product_id);

                $productSaleEntity = ProductSaleMapper::fromArray(
                    [
                        'product_id' => $product->product_id,
                        'name' => $productModel->name,
                        'price' => $productModel->price->getPrice(),
                        'amount' => $product->amount
                    ]
                );

                $this->sale->products->update($productSaleEntity);
            } catch (\Exception $ex) {
                throw new ProductNotExistsException("product_id");
            }
        }
        return $this->saleRepository->update($this->sale);
    }
}
