<?php

namespace App\Repositories\Agenda;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AgendaRepositoryInterface
{
    public function find(int $id): ?Agenda;
    public function create(array $data): Agenda;
    public function update(string $id, array $data): ?Agenda;
    public function delete(string $id): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
}
