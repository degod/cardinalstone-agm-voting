@extends('layout.master')

@section('title', 'AGM Management')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">AGM Management</h1>

    <a href="#" data-bs-toggle="modal" data-bs-target="#addAgmModal" class="btn btn-success mb-3 float-end">Add New AGM</a>

    <div class="modal fade" id="addAgmModal" tabindex="-1" aria-labelledby="addAgmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgmModalLabel">Add New AGM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('agms.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
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
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="meeting_date" class="form-label">Meeting Date</label>
                            <input type="datetime-local" class="form-control" id="meeting_date" name="meeting_date" min="{{ now()->format('Y-m-d\\TH:i') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="voting_start_time" class="form-label">Voting Start Date</label>
                                    <input type="datetime-local" class="form-control" id="voting_start_time" name="voting_start_time" min="{{ now()->format('Y-m-d\\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="voting_end_time" class="form-label">Voting End Date</label>
                                    <input type="datetime-local" class="form-control" id="voting_end_time" name="voting_end_time" min="{{ now()->format('Y-m-d\\TH:i') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value=""> -- Select Status --</option>
                                @foreach($agmStatuses as $statusKey=>$statusValue)
                                <option value="{{ $statusKey }}">{{ $statusValue }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add AGM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Company</th>
                <th>AGM Title</th>
                <th>Description</th>
                <th>Meeting Date</th>
                <th>Voting Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agms as $agm)
            <tr>
                <td>{{ $agm->company->name }}</td>
                <td>{{ $agm->title }}</td>
                <td>{{ $agm->description }}</td>
                <td>{{ \Carbon\Carbon::parse($agm->meeting_date)->format('F jS, Y g:s a') }}</td>
                <td>
                    <small style="display:block;white-space:nowrap;">
                        <b>Start:</b> {{ \Carbon\Carbon::parse($agm->voting_start_time)->format('F jS, Y g:s a') }}
                    </small>
                    <small style="display:block;white-space:nowrap;">
                        <b>End:</b> {{ \Carbon\Carbon::parse($agm->voting_end_time)->format('F jS, Y g:s a') }}
                    </small>
                </td>
                <td>{{ $agm->status }}</td>
                <td>
                    <a href="{{ route('agms.view', $agm->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('agms.edit', $agm->id) }}" class="btn btn-primary btn-sm">Edit</a>

                    <form action="{{ route('agms.destroy', $agm->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No companies found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($agms->hasPages())
    {{ $agms->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection