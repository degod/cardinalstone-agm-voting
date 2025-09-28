<?php

namespace App\Repositories\Agm;

use App\Models\Agm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AgmRepositoryInterface
{
    public function find(int $id): ?Agm;
    public function create(array $data): Agm;
    public function update(string $id, array $data): ?Agm;
    public function delete(string $id): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
}
