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

    public function findGrouped(int $id): ?Agenda
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
        $agenda = Agenda::hydrate([$attrs])->first();
        $agenda->setRelation('items', $group->values());

        return $agenda;
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
        $query = $this->model->orderBy('id', 'DESC');
        $records = $perPage ? $query->paginate($perPage) : $query->get();
        $grouped = $records->groupBy(function ($item) {
            return $item->agenda_uuid . '-' . $item->agm_id;
        })->map(function ($group) {
            $first = $group->first();

            $attrs = [
                'id'          => $first->id,
                'agenda_uuid' => $first->agenda_uuid,
                'agm_id'      => $first->agm_id,
                'description' => $first->description,
                'is_active'   => $first->is_active,
            ];
            $agenda = Agenda::hydrate([$attrs])->first();
            $agenda->setRelation('items', $group->sortBy('item_number')->values());

            return $agenda;
        })->values();

        if ($records instanceof LengthAwarePaginator) {
            return new LengthAwarePaginator(
                $grouped,
                $records->total(),
                $records->perPage(),
                $records->currentPage(),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return $grouped;
    }
}