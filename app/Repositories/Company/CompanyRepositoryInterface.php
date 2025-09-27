<?php

namespace App\Repositories\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    public function find(int $id): ?Company;
    public function create(array $data): Company;
    public function update(string $id, array $data): ?Company;
    public function delete(string $id): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
}
