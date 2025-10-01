<?php

namespace App\Http\Controllers;

use App\Enums\VoteValues;
use App\Http\Requests\VoteStoreRequest;
use App\Repositories\Agenda\AgendaRepositoryInterface;
use App\Repositories\Vote\VoteRepositoryInterface;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function __construct(private AgendaRepositoryInterface $agendaRepository, private VoteRepositoryInterface $voteRepository, private LogService $logService) {}

    public function index()
    {
        $votes = $this->voteRepository->allGrouped(config('pagination.default.per_page'), []);

        return view('votes.index', compact('votes'));
    }

    public function vote()
    {
        $user = Auth::user();
        $agms = $user->companies->flatMap(function ($company) {
            return $company->agms;
        });
        $voteTypes = VoteValues::asKeyValue();
        $votes = $this->voteRepository->allUserVotes($user->id);

        return view('votes.vote', compact('agms', 'voteTypes', 'user', 'votes'));
    }

    public function store(VoteStoreRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $votesCast = $data['votes_cast'] ?? 1;
        $voteValues = $data['vote_value'] ?? [];

        try {
            foreach ($voteValues as $agendaId => $voteValue) {
                $this->voteRepository->create([
                    'user_id' => $user->id,
                    'agenda_id' => $agendaId,
                    'vote_value' => $voteValue,
                    'votes_cast' => $votesCast,
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'You already voted for this agenda.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error creating Vote: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agendas.index')->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Votes submitted successfully.');
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
