@extends('layout.master')

@section('title', 'Vote List')

@section('body_content')
<div class="container mt-5">
    <h1 class="mb-4">Vote List</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>AGM</th>
                <th>Agenda Item</th>
                <th>Vote Casted</th>
                <th>Vote Values</th>
                <th>Items Summary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($votes->items() as $vote)
            <tr>
                <td>{{ $vote->agenda->agm->title }}</td>
                <td>{{ $vote->agenda->title }}</td>
                <td>
                    {{ $vote->users_count }}<br>
                    <small style="white-space:nowrap">
                        <b>VALUE:</b> {{ $vote->vote_cast_total }}
                    </small>
                </td>
                <td>
                    @foreach($vote->vote_value_all as $k=>$v)
                    <small style="display:block;white-space:nowrap">
                        <b>{{ $k }}:</b> {{ $v }}
                    </small>
                    @endforeach
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#voteModal{{ $vote->id }}">
                        View
                    </button>
                    <div class="modal fade" id="voteModal{{ $vote->id }}" tabindex="-1"
                        aria-labelledby="voteModalLabel{{ $vote->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="voteModalLabel{{ $vote->id }}">
                                        Votes on this item
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <p class="mb-1"><b>Company:</b> {{ $vote->agenda->agm->company->name }}</p>
                                    <p class="mb-1"><b>AGM:</b> {{ $vote->agenda->agm->title }}</p>
                                    <p class="mb-1"><b>Title:</b> {{ $vote->agenda->title }}</p>
                                    <p class="mb-1"><b>Description:</b> {{ $vote->agenda->description }}</p>

                                    <hr>
                                    <h6>Shareholder Votes</h6>
                                    <table class="table table-striped table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Vote Value</th>
                                                <th>Votes Cast</th>
                                                <th>Voted At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vote->items as $shareholderVote)
                                            <tr>
                                                <td>{{ $shareholderVote->user->name ?? 'N/A' }}</td>
                                                <td>{{ $shareholderVote->vote_value }}</td>
                                                <td>{{ $shareholderVote->votes_cast }}</td>
                                                <td>{{ \Carbon\Carbon::parse($shareholderVote->voted_at)->format('F jS, Y g:s a') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <form action="{{ route('votes.destroy', $vote->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No Votes found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($votes->hasPages())
    {{ $votes->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection