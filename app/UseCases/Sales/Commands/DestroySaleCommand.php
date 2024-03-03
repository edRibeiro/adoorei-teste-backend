<?php

namespace App\UseCases\Sales\Commands;

use App\Repositories\Contracts\SaleRepositoryInterface;
use App\UseCases\CommandInterface;


class DestroySaleCommand implements CommandInterface
{
    private SaleRepositoryInterface $repository;
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->repository = app()->make(SaleRepositoryInterface::class);
    }

    public function execute(): mixed
    {
        return $this->repository->delete($this->id);
    }
}
