<?php

namespace App\Repositories\Agenda;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AgendaRepositoryInterface
{
    public function find(int $id): ?Agenda;
    public function findGrouped(int $id): ?Agenda;
    public function create(array $data): Agenda;
    public function update(string $id, array $data): ?Agenda;
    public function delete(string $uuid): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
    public function allGrouped(?int $perPage): LengthAwarePaginator|Collection;
}