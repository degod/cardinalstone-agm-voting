<?php

namespace App\Repositories\Vote;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface VoteRepositoryInterface
{
    public function find(int $id): ?Vote;
    public function findGrouped(int $id): ?Vote;
    public function create(array $data): Vote;
    public function update(string $id, array $data): ?Vote;
    public function delete(string $uuid): bool;
    public function all(?int $perPage): LengthAwarePaginator|Collection;
    public function allGrouped(?int $perPage): LengthAwarePaginator|Collection;
    public function allUserVotes(int $userId): Collection;
}
