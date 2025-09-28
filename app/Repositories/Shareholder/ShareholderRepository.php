<?php

namespace App\Repositories\Shareholder;

use App\Models\Shareholder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ShareholderRepository implements ShareholderRepositoryInterface
{
    public function __construct(private Shareholder $model)
    {
        $this->model = $model;
    }

    public function find(int $id): ?Shareholder
    {
        return $this->model->find($id);
    }

    public function create(array $data): Shareholder
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): ?Shareholder
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
