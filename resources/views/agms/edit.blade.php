@extends('layout.master')
@section('title', 'AGM Edit')

@section('body_content')
<h3>AGM Edit</h3>
<p>Updating {{ $agm->name }} Details</p>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('agms.update', $agm->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $agm->title) }}" required>
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company</label>
                <input type="text" class="form-control" id="company" readonly value="{{ $agm->company->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description', $agm->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="meeting_date" class="form-label">Meeting Date</label>
                <input type="datetime-local" class="form-control" id="meeting_date" name="meeting_date" value="{{ old('meeting_date', \Carbon\Carbon::parse($agm->meeting_date)->format('Y-m-d\\TH:i') ) }}" min="{{ now()->format('Y-m-d\\TH:i') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="voting_start_time" class="form-label">Voting Start Date</label>
                        <input type="datetime-local" class="form-control" id="voting_start_time" name="voting_start_time" value="{{ old('voting_start_time', \Carbon\Carbon::parse($agm->voting_start_time)->format('Y-m-d\\TH:i') ) }}" min="{{ now()->format('Y-m-d\\TH:i') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="voting_end_time" class="form-label">Voting End Date</label>
                        <input type="datetime-local" class="form-control" id="voting_end_time" name="voting_end_time" value="{{ old('voting_end_time', \Carbon\Carbon::parse($agm->voting_end_time)->format('Y-m-d\\TH:i') ) }}" min="{{ now()->format('Y-m-d\\TH:i') }}" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value=""> -- Select Status --</option>
                    @foreach($agmStatuses as $statusKey=>$statusValue)
                    <option value="{{ $statusKey }}" {{ $agm->status == $statusKey ? 'selected' : '' }}>{{ $statusValue }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update AGM</button>
        </form>
    </div>
</div>
@endsection