<?php

namespace App\Repositories\Vote;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VoteRepository implements VoteRepositoryInterface
{
    public function __construct(private Vote $model)
    {
        $this->model = $model;
    }

    public function find(int $id): ?Vote
    {
        return $this->model->find($id);
    }

    public function findGrouped(int $id): ?Vote
    {
        $record = $this->model->find($id);
        if (! $record) {
            return null;
        }
        $group = $this->model
            ->where('agenda_uuid', $record->agenda_uuid)
            ->where('agm_id', $record->agm_id)
            ->orderBy('item_number')
            ->get();

        if ($group->isEmpty()) {
            return null;
        }
        $first = $group->first();
        $attrs = [
            'id'          => $first->id,
            'agenda_uuid' => $first->agenda_uuid,
            'agm_id'      => $first->agm_id,
            'description' => $first->description,
            'is_active'   => $first->is_active,
        ];
        $agenda = Vote::hydrate([$attrs])->first();
        $agenda->setRelation('items', $group->values());

        return $agenda;
    }

    public function create(array $data): Vote
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): ?Vote
    {
        $record = $this->find($id);
        if (!$record) return null;
        $record->update($data);
        return $record;
    }

    public function delete(string $uuid): bool
    {
        $records = $this->model->where('agenda_uuid', $uuid);
        if (!$records->get()) return false;
        return $records->delete();
    }

    public function all(?int $perPage): LengthAwarePaginator|Collection
    {
        $users = $this->model->orderBy('id', 'DESC');
        return $perPage ? $users->paginate($perPage) : $users->get();
    }

    public function allGrouped(?int $perPage): LengthAwarePaginator|Collection
    {
        // Get all votes
        $votes = $this->model->orderBy('agenda_id', 'DESC')->get();

        // Group by agenda_id
        $grouped = $votes->groupBy('agenda_id')->map(function ($group) {
            $first = $group->first();
            if (!$first) return null;
            $attrs = [
                'id'              => $first->id,
                'agenda_id'       => $first->agenda_id,
                'users_count'     => $group->pluck('user_id')->unique()->count(),
                'vote_cast_total' => $group->sum('votes_cast'),
                'vote_value_all'  => $group->groupBy('vote_value')
                    ->map(fn($votes) => $votes->sum('votes_cast'))
                    ->toArray(),
            ];
            $agenda = Vote::hydrate([$attrs])->first();
            $agenda->setRelation('items', $group->sortBy('voted_at')->values());
            return $agenda;
        })->filter()->values();

        if ($perPage) {
            $page = LengthAwarePaginator::resolveCurrentPage();
            $paged = $grouped->forPage($page, $perPage)->values();
            return new LengthAwarePaginator(
                $paged,
                $grouped->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return $grouped;
    }
}
