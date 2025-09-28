<?php

namespace App\Repositories\Shareholder;

use App\Models\Shareholder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ShareholderRepositoryInterface
{
    public function find(int $id): ?Shareholder;
    public function create(array $data): Shareholder;
    public function update(string $id, array $data): ?Shareholder;
    public function delete(string $id): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
}
