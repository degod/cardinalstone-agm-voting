<?php

namespace App\Http\Controllers;

use App\Enums\ShareholderStatuses;
use App\Http\Requests\ShareholderEditRequest;
use App\Http\Requests\ShareholderStoreRequest;
use App\Repositories\Agm\AgmRepositoryInterface;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Shareholder\ShareholderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\LogService;

class ShareholderController extends Controller
{
    public function __construct(private ShareholderRepositoryInterface $shareholderRepository, private AgmRepositoryInterface $agmRepository, private CompanyRepositoryInterface $companyRepository, private UserRepositoryInterface $userRepository, private LogService $logService) {}

    public function index()
    {
        $shareholders = $this->shareholderRepository->all(config('pagination.default.per_page'), []);
        $companies = $this->companyRepository->all(null, []);
        $users = $this->userRepository->all(null, []);
        $shareholderStatuses = ShareholderStatuses::asKeyValue();

        return view('shareholders.index', compact('shareholders', 'companies', 'users', 'shareholderStatuses'));
    }

    public function store(ShareholderStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $this->shareholderRepository->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This Shareholder already exists.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error creating Shareholder: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('shareholders.index')->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Shareholder created successfully.');
    }

    public function edit(int $id)
    {
        $shareholder = $this->shareholderRepository->find($id);
        if (!$shareholder) {
            return redirect()->route('shareholders.index')->with('error', 'Shareholder not found.');
        }
        $shareholderStatuses = ShareholderStatuses::asKeyValue();

        return view('shareholders.edit', compact('shareholder', 'shareholderStatuses'));
    }

    public function update(ShareholderEditRequest $request, int $id)
    {
        $shareholder = $this->shareholderRepository->find($id);
        if (!$shareholder) {
            return redirect()->route('shareholders.index')->with('error', 'Shareholder not found.');
        }

        $data = $request->validated();

        try {
            $this->shareholderRepository->update($id, $data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This Shareholder already exists for this company.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error updating Shareholder: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('shareholders.index')->with('error', $e->getMessage());
        }

        return redirect()->route('shareholders.index')->with('success', 'Shareholder updated successfully.');
    }

    public function destroy(int $id)
    {
        $shareholder = $this->shareholderRepository->find($id);
        if (!$shareholder) {
            return redirect()->route('shareholders.index')->with('error', 'Shareholder not found.');
        }

        try {
            $this->shareholderRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting Shareholder: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('shareholders.index')->with('error', $e->getMessage());
        }

        return redirect()->route('shareholders.index')->with('success', 'Shareholder deleted successfully.');
    }
}
