<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatuses;
use App\Enums\ItemTypes;
use App\Enums\VoteTypes;
use App\Http\Requests\AgendaEditRequest;
use App\Http\Requests\AgendaStoreRequest;
use App\Repositories\Agenda\AgendaRepositoryInterface;
use App\Repositories\Agm\AgmRepositoryInterface;
use App\Services\LogService;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    public function __construct(private AgendaRepositoryInterface $agendaRepository, private AgmRepositoryInterface $agmRepository, private LogService $logService) {}

    public function index()
    {
        $agendas = $this->agendaRepository->allGrouped(config('pagination.default.per_page'), []);
        $agms = $this->agmRepository->all(null, []);
        $voteTypes = VoteTypes::asKeyValue();
        $itemStatuses = ItemStatuses::asKeyValue();
        $itemTypes = ItemTypes::asKeyValue();

        return view('agendas.index', compact('agendas', 'agms', 'itemTypes', 'itemStatuses', 'voteTypes'));
    }

    public function view(int $id)
    {
        $agenda = $this->agendaRepository->find($id);
        if (!$agenda) {
            return redirect()->route('agendas.index')->with('error', 'Agenda not found.');
        }
        // dd($agenda->votes);
        $voteTypes = VoteTypes::asKeyValue();
        $itemStatuses = ItemStatuses::asKeyValue();
        $itemTypes = ItemTypes::asKeyValue();

        return view('agendas.view', compact('agenda', 'voteTypes', 'itemStatuses', 'itemTypes'));
    }

    public function store(AgendaStoreRequest $request)
    {
        $data = $request->validated();
        $uuid = Str::uuid()->toString();

        try {
            foreach ($data['items'] as $item) {
                $item['description'] = $data['description'] ?? null;
                $item['agm_id'] = $data['agm_id'];
                $item['agenda_uuid'] = ItemStatuses::ACTIVE;
                $item['agenda_uuid'] = $uuid;
                $this->agendaRepository->create($item);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This Agenda already exists.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error creating Agenda: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agendas.index')->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Agenda created successfully.');
    }

    public function edit(int $id)
    {
        $agenda = $this->agendaRepository->findGrouped($id);
        if (!$agenda) {
            return redirect()->route('agendas.index')->with('error', 'Agenda not found.');
        }
        $voteTypes = VoteTypes::asKeyValue();
        $itemStatuses = ItemStatuses::asKeyValue();
        $itemTypes = ItemTypes::asKeyValue();

        return view('agendas.edit', compact('agenda', 'voteTypes', 'itemStatuses', 'itemTypes'));
    }

    public function update(AgendaEditRequest $request, int $id)
    {
        $agenda = $this->agendaRepository->find($id);
        if (!$agenda) {
            return redirect()->route('agendas.index')->with('error', 'Agenda not found.');
        }
        $data = $request->validated();

        try {
            foreach ($data['items'] as $item) {
                if (isset($item['id'])) {
                    $this->agendaRepository->update($item['id'], $item);
                } else {
                    $item['description'] = $data['description'] ?? null;
                    $item['agm_id'] = $agenda->agm_id;
                    $item['agenda_uuid'] = $agenda->agenda_uuid;
                    $this->agendaRepository->create($item);
                }
            }
            $this->agendaRepository->update($id, $data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This Agenda already exists for this company.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error updating Agenda: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agendas.index')->with('error', $e->getMessage());
        }

        return redirect()->route('agendas.index')->with('success', 'Agenda updated successfully.');
    }

    public function destroy(int $id)
    {
        $agenda = $this->agendaRepository->find($id);
        if (!$agenda) {
            return redirect()->route('agendas.index')->with('error', 'Agenda not found.');
        }

        try {
            $this->agendaRepository->delete($agenda->agenda_uuid);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting Agenda: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agendas.index')->with('error', $e->getMessage());
        }

        return redirect()->route('agendas.index')->with('success', 'Agenda deleted successfully.');
    }
}
