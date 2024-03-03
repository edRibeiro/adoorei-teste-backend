<?php

namespace App\Http\Controllers\Api;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Dtos\SaleDto;
use App\Http\Controllers\Controller;
use App\Mappers\SaleMapper;
use App\UseCases\Sales\Commands\AddProductFromSaleCommand;
use App\UseCases\Sales\Commands\DestroySaleCommand;
use App\UseCases\Sales\Commands\StoreSaleCommand;
use App\UseCases\Sales\Queries\FindAllSalesQuery;
use App\UseCases\Sales\Queries\FindSaleByIdQuery;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->success((new FindAllSalesQuery())->handle());
        } catch (Exception $e) {
            return response()->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $newSale = SaleMapper::fromRequest($request);
            $sale = (new StoreSaleCommand($newSale))->execute();
            return response()->success($sale, Response::HTTP_CREATED);
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            return response()->success((new FindSaleByIdQuery($id))->handle());
        } catch (ResourceNotFoundException $e) {
            return response()->error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $sale = SaleDto::fromRequest($request, $id);
            (new AddProductFromSaleCommand($sale))->execute();
            return response()->success((new FindSaleByIdQuery($id))->handle());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            (new DestroySaleCommand($id))->execute();
            return response()->success(null, Response::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $e) {
            return response()->error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
