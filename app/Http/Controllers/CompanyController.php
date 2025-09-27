<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyEditRequest;
use App\Http\Requests\CompanyStoreRequest;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Services\LogService;

class CompanyController extends Controller
{
    public function __construct(private CompanyRepositoryInterface $companyRepository, private LogService $logService) {}

    public function index()
    {
        $companies = $this->companyRepository->all(config('pagination.default.per_page'), []);

        return view('companies.index', compact('companies'));
    }

    public function store(CompanyStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $this->companyRepository->create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This company already exists.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error creating company: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('companies.index')->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Company created successfully.');
    }

    public function edit(int $id)
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            return redirect()->route('companies.index')->with('error', 'Company not found.');
        }

        return view('companies.edit', compact('company'));
    }

    public function update(CompanyEditRequest $request, int $id)
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            return redirect()->route('companies.index')->with('error', 'Company not found.');
        }

        $data = $request->validated();

        try {
            $this->companyRepository->update($id, $data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return redirect()->back()->with('error', 'This company already exists for this house owner.');
            }
            return redirect()->back()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            $this->logService->error('Error updating company: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('companies.index')->with('error', $e->getMessage());
        }

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(int $id)
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            return redirect()->route('companies.index')->with('error', 'Company not found.');
        }

        try {
            $this->companyRepository->delete($id);
        } catch (\Exception $e) {
            $this->logService->error('Error deleting company: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('companies.index')->with('error', $e->getMessage());
        }

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
