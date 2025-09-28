<?php

namespace App\Http\Controllers;

use App\Enums\AgmStatuses;
use App\Http\Requests\AgmEditRequest;
use App\Http\Requests\AgmStoreRequest;
use App\Repositories\Agm\AgmRepositoryInterface;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Services\LogService;

class AgmController extends Controller
{
    public function __construct(private AgmRepositoryInterface $agmRepository, private CompanyRepositoryInterface $companyRepository, private LogService $logService) {}

    public function index()
    {
        $agms = $this->agmRepository->all(config('pagination.default.per_page'), []);
        $companies = $this->companyRepository->all(null, []);
        $agmStatuses = AgmStatuses::asKeyValue();

        return view('agms.index', compact('agms', 'companies', 'agmStatuses'));
    }

    public function store(AgmStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $this->agmRepository->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This AGM already exists.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->logService->error('Error creating AGM: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agms.index')->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'AGM created successfully.');
    }

    public function edit(int $id)
    {
        $agm = $this->agmRepository->find($id);
        if (!$agm) {
            return redirect()->route('agms.index')->with('error', 'AGM not found.');
        }
        $agmStatuses = AgmStatuses::asKeyValue();

        return view('agms.edit', compact('agm', 'agmStatuses'));
    }

    public function update(AgmEditRequest $request, int $id)
    {
        $agm = $this->agmRepository->find($id);
        if (!$agm) {
            return redirect()->route('agms.index')->with('error', 'AGM not found.');
        }

        $data = $request->validated();

        try {
            $this->agmRepository->update($id, $data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This AGM already exists for this house owner.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error updating AGM: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agms.index')->with('error', $e->getMessage());
        }

        return redirect()->route('agms.index')->with('success', 'AGM updated successfully.');
    }

    public function destroy(int $id)
    {
        $agm = $this->agmRepository->find($id);
        if (!$agm) {
            return redirect()->route('agms.index')->with('error', 'AGM not found.');
        }

        try {
            $this->agmRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting AGM: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('agms.index')->with('error', $e->getMessage());
        }

        return redirect()->route('agms.index')->with('success', 'AGM deleted successfully.');
    }
}
