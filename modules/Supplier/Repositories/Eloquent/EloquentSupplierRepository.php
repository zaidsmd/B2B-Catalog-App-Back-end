<?php

namespace Modules\Supplier\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Supplier\Contracts\Repositories\SupplierRepository;
use Modules\Supplier\DTOs\CreateSupplierDTO;
use Modules\Supplier\DTOs\UpdateSupplierDTO;
use Modules\Supplier\Models\Supplier;

class EloquentSupplierRepository implements SupplierRepository
{

    public function __construct(public Supplier $model) {}
    public function query(array $with = []): Builder
    {
        return $this->model->newQuery()->with($with);
    }

    public function create(CreateSupplierDTO $attributes): Supplier
    {
        return $this->model->newQuery()->create($attributes->toArray());
    }

    public function update(Supplier $supplier, UpdateSupplierDTO $attributes): bool
    {
        return $supplier->update($attributes->toArray());
    }

    public function delete(Supplier $supplier): bool
    {
        return $supplier->delete();
    }

    public function findById(int $id, array $with = []): Collection|Model
    {
        return $this->query($with)->find($id);
    }
}
