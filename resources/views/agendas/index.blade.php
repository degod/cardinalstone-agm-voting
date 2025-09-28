@extends('layout.master')

@section('title', 'Agenda Management')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Agenda Management</h1>

    <a href="#" data-bs-toggle="modal" data-bs-target="#addAgendaModal" class="btn btn-success mb-3 float-end">Add New Agenda</a>

    <div class="modal fade" id="addAgendaModal" tabindex="-1" aria-labelledby="addAgendaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgendaModalLabel">Add New Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('agendas.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Shareholder</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value=""> -- Select Shareholder --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company</label>
                            <select class="form-select" id="company_id" name="company_id" required>
                                <option value=""> -- Select Company --</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="shares_owned" class="form-label">Number of shares</label>
                            <input type="number" class="form-control" id="shares_owned" name="shares_owned" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="share_certificate_number" class="form-label">Certificate Number</label>
                            <input type="text" class="form-control" id="share_certificate_number" name="share_certificate_number">
                        </div>
                        <div class="mb-3">
                            <label for="acquired_date" class="form-label">Acquisition Date</label>
                            <input type="date" class="form-control" id="acquired_date" name="acquired_date" min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value=""> -- Select Status --</option>
                                @foreach($shareholderStatuses as $statusKey=>$statusValue)
                                <option value="{{ $statusKey }}">{{ $statusValue }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Agenda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>AGM</th>
                <th>Shareholder</th>
                <th>Shares Owned</th>
                <th>Cert. No.</th>
                <th>Date Acquired</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shareholders as $shareholder)
            <tr>
                <td>{{ $shareholder->company->name }}</td>
                <td>{{ $shareholder->user->name }}</td>
                <td>{{ $shareholder->shares_owned }} unit(s)</td>
                <td>{{ $shareholder->share_certificate_number }}</td>
                <td>{{ \Carbon\Carbon::parse($shareholder->acquired_date)->format('F jS, Y') }}</td>
                <td>{{ $shareholderStatuses[$shareholder->is_active] }}</td>
                <td>
                    <a href="{{ route('agendas.edit', $shareholder->id) }}" class="btn btn-primary btn-sm">Edit</a>

                    <form action="{{ route('agendas.destroy', $shareholder->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No Shareholders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($shareholders->hasPages())
    {{ $shareholders->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection