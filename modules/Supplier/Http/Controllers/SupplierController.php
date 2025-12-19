<?php

namespace Modules\Supplier\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Supplier\DTOs\CreateSupplierDTO;
use Modules\Supplier\DTOs\SupplierFilterDTO;
use Modules\Supplier\DTOs\UpdateSupplierDTO;
use Modules\Supplier\Enums\SupplierType;
use Modules\Supplier\Http\Requests\SupplierIndexRequest;
use Modules\Supplier\Http\Requests\SupplierStoreRequest;
use Modules\Supplier\Http\Requests\SupplierUpdateRequest;
use Modules\Supplier\Http\Resources\SupplierResource;
use Modules\Supplier\Models\Supplier;
use Modules\Supplier\Services\SupplierService;
use Yajra\DataTables\Exceptions\Exception;

class SupplierController extends Controller
{
    public function __construct(public SupplierService $supplierService) {}

    /**
     * @throws Exception
     */
    public function index(SupplierIndexRequest $request): JsonResponse
    {
        $dto = SupplierFilterDTO::fromArray($request->validated());

        return $this->supplierService->index($dto);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'types' => array_map(fn ($case) => [
                'label' => $case->getLabel(),
                'value' => $case->value,
            ], SupplierType::cases()),
        ]);
    }

    public function store(SupplierStoreRequest $request): JsonResponse
    {
        $dto = CreateSupplierDTO::fromArray($request->validated());
        $supplier = $this->supplierService->store($dto);

        return new SupplierResource($supplier)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Supplier $supplier): SupplierResource
    {
        return new SupplierResource($supplier);
    }

    public function edit(Supplier $supplier): SupplierResource
    {
        return new SupplierResource($supplier);
    }

    public function update(SupplierUpdateRequest $request, Supplier $supplier): SupplierResource
    {
        $dto = UpdateSupplierDTO::fromArray($request->validated());
        $this->supplierService->update($supplier, $dto);

        return new SupplierResource($supplier->fresh());
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->supplierService->destroy($supplier);

        return response()->json(['message'=>__('supplier.messages.deleted')], 200);
    }
}
