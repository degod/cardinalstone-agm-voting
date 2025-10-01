@extends('layout.master')
@section('title', 'AGM View')

@section('body_content')
<h3>AGM View</h3>
<p>Viewing {{ $agm->title }} Details</p>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('agms.update', $agm->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input class="form-control" value="{{ $agm->title }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Company</label>
                <input class="form-control" value="{{ $agm->company->name }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" readonly>{{ $agm->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="meeting_date" class="form-label">Meeting Date</label>
                <input class="form-control" value="{{ \Carbon\Carbon::parse($agm->meeting_date)->format('Y-m-d\\TH:i') }}" readonly>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Voting Start Date</label>
                        <input class="form-control" value="{{ \Carbon\Carbon::parse($agm->voting_start_time)->format('Y-m-d\\TH:i') }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Voting End Date</label>
                        <input class="form-control" value="{{ \Carbon\Carbon::parse($agm->voting_end_time)->format('Y-m-d\\TH:i') }}" readonly>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" readonly>
                    @foreach($agmStatuses as $statusKey=>$statusValue)
                    @if($agm->status == $statusKey)
                    <option>{{ $statusValue }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="col-md-6">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Agenda Item Title</th>
                    <th>Type</th>
                    <th>Votes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agm->agendas as $agenda)
                <tr>
                    <td>{{ $agenda->title }}</td>
                    <td>{{ $agenda->voting_type }}</td>
                    <td>{{ $agenda->votes->count() }}</td>
                    <td>
                        <a href="{{ route('agendas.view', $agenda->id) }}" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection