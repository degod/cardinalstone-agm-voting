<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteEditRequest;
use App\Http\Requests\VoteStoreRequest;
use App\Repositories\Agenda\AgendaRepositoryInterface;
use App\Repositories\Vote\VoteRepositoryInterface;
use App\Services\LogService;

class VoteController extends Controller
{
    public function __construct(private AgendaRepositoryInterface $agendaRepository, private VoteRepositoryInterface $voteRepository, private LogService $logService) {}

    public function index()
    {
        $votes = $this->voteRepository->allGrouped(config('pagination.default.per_page'), []);

        return view('votes.index', compact('votes'));
    }

    public function store(VoteStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $this->voteRepository->create($data);
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

        return view('agendas.edit', compact('agenda', 'voteTypes', 'itemStatuses', 'itemTypes'));
    }

    public function update(VoteEditRequest $request, int $id)
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
