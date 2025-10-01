@extends('layout.master')
@section('title', 'Agenda View')

@section('body_content')
<h3>Agenda Item View</h3>
<p>Viewing agenda item number {{ $agenda->item_number }}</p>

<div class="row">
    <div class="col-lg-6">
        <div class="mb-3">
            <label class="form-label">AGM</label>
            <input class="form-control" readonly value="{{ $agenda->agm->title }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Agenda Title</label>
            <input class="form-control" readonly value="{{ $agenda->title }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Agenda Description</label>
            <textarea class="form-control" rows="3"
                readonly>{{ $agenda->description }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" required>
                @foreach($itemStatuses as $statusKey=>$statusValue)
                @if($agenda->is_active == $statusKey)
                <option>{{ $statusValue }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Item Type</label>
            <select class="form-select me-2">
                @foreach($itemTypes as $key => $label)
                @if($agenda->item_type == $key)
                <option>{{ $label }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Voting Type</label>
            <select class="form-select me-2">
                @foreach($voteTypes as $key => $label)
                @if($agenda->voting_type == $key)
                <option>{{ $label }}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Shareholder</th>
                        <th>Vote Value</th>
                        <th>Shares</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agenda->votes as $vote)
                    <tr>
                        <td>{{ $vote->user->name ?? 'N/A' }}</td>
                        <td>{{ $vote->vote_value ?? 'N/A' }}</td>
                        <td>{{ $vote->votes_cast ?? 'N/A' }}</td>
                        <td>{{ $vote->voted_at ? $vote->voted_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No votes recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection