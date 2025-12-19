<?php

namespace Modules\Supplier\Services;

use Illuminate\Http\JsonResponse;
use Modules\Supplier\Contracts\Repositories\SupplierRepository;
use Modules\Supplier\DTOs\CreateSupplierDTO;
use Modules\Supplier\DTOs\SupplierFilterDTO;
use Modules\Supplier\DTOs\UpdateSupplierDTO;
use Modules\Supplier\Http\Resources\SupplierIndexResource;
use Modules\Supplier\Models\Supplier;
use Yajra\DataTables\Exceptions\Exception;

class SupplierService
{
    public function __construct(public SupplierRepository $supplierRepository) {}

    /**
     * @throws Exception
     */
    public function index(SupplierFilterDTO $filterDTO): JsonResponse
    {

        $query = $this->supplierRepository->query();
        foreach ([
            'full_name',
            'email',
            'phone',
            'type',
            'address',
        ] as $field) {
            $query->when(
                $filterDTO->{$field},
                fn ($q, $value) => $q->where($field, 'like', "%{$value}%")
            );
        }

        return datatables()
            ->eloquent($query)
            ->setTransformer(fn (Supplier $supplier) => SupplierIndexResource::make($supplier)->resolve())
            ->toJson();
    }

    public function store(CreateSupplierDTO $dto): Supplier
    {
        return $this->supplierRepository->create($dto);
    }

    public function show(int $id): Supplier
    {
        return $this->supplierRepository->findById($id);
    }

    public function update(Supplier $supplier, UpdateSupplierDTO $dto): bool
    {
        return $this->supplierRepository->update($supplier, $dto);
    }

    public function destroy(Supplier $supplier): bool
    {
        return $this->supplierRepository->delete($supplier);
    }
}
