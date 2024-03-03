<?php

namespace App\UseCases\Sales\Commands;

use App\Domain\Exceptions\ProductNotExistsException;
use App\Domain\Sale\Sale as SaleEntity;
use App\Dtos\SaleDto;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\UseCases\CommandInterface;

class StoreSaleCommand implements CommandInterface
{
    private SaleEntity $sale;
    private SaleRepositoryInterface $saleRepository;
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        SaleEntity $sale
    ) {
        $this->sale = $sale;
        $this->saleRepository = app()->make(SaleRepositoryInterface::class);
        $this->productRepository = app()->make(ProductRepositoryInterface::class);
    }

    public function execute(): SaleDto
    {
        foreach ($this->sale->getProducts() as $product) {
            try {
                $this->productRepository->findById($product->product_id);
            } catch (\Exception $ex) {
                throw new ProductNotExistsException("product_id");
            }
        }
        return $this->saleRepository->store($this->sale);
    }
}
