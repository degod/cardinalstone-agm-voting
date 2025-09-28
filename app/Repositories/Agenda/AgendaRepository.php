<?php

namespace App\Repositories\Agenda;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AgendaRepository implements AgendaRepositoryInterface
{
    public function __construct(private Agenda $model)
    {
        $this->model = $model;
    }

    public function find(int $id): ?Agenda
    {
        return $this->model->find($id);
    }

    public function create(array $data): Agenda
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): ?Agenda
    {
        $record = $this->find($id);
        if (!$record) return null;
        $record->update($data);
        return $record;
    }

    public function delete(string $id): bool
    {
        $record = $this->find($id);
        if (!$record) return false;
        return $record->delete();
    }

    public function all(?int $perPage): LengthAwarePaginator|Collection
    {
        $users = $this->model->orderBy('id', 'DESC');
        return $perPage ? $users->paginate($perPage) : $users->get();
    }
}
