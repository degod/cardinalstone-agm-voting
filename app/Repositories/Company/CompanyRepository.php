<?php

namespace App\Repositories\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(private Company $model)
    {
        $this->model = $model;
    }

    public function find(int $id): ?Company
    {
        return $this->model->find($id);
    }

    public function create(array $data): Company
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): ?Company
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
