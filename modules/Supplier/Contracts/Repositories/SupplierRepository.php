<?php

namespace Modules\Supplier\Contracts\Repositories;
use Illuminate\Database\Eloquent\Builder;
use Modules\Supplier\DTOs\CreateSupplierDTO;
use Modules\Supplier\DTOs\UpdateSupplierDTO;
use Modules\Supplier\Models\Supplier;

interface SupplierRepository
{
    public function query(array $with = []): Builder;

    public function create(CreateSupplierDTO $attributes): Supplier;

    public function update(Supplier $supplier, UpdateSupplierDTO $attributes): bool;

    public function delete(Supplier $supplier): bool;

    public function findById(int $id, array $with = []);
}
