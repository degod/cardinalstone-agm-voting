@extends('layout.master')
@section('title', 'Vote')

@section('body_content')
<h3>Pick an Agenda to Vote</h3>

<div class="row">
    <div class="col-md-12">
        <div class="accordion" id="agmAccordion">
            @foreach($agms as $index => $agm)
            <div class="accordion-item">
                <h2 class="accordion-header mb-2" id="heading{{ $index }}">
                    <button class="accordion-button @if($index!==0) collapsed @endif bg-secondary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="@if($index===0)true @else false @endif" aria-controls="collapse{{ $index }}">
                        <div class="d-flex w-100 align-items-center">
                            <span>{{ $agm->title }} [{{ $agm->company->name }}]</span>
                            <span class="ms-auto me-2">{{ \Carbon\Carbon::parse($agm->meeting_date)->toFormattedDateString() }}</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $index }}" class="accordion-collapse collapse @if($index===0) show @endif" aria-labelledby="heading{{ $index }}" data-bs-parent="#agmAccordion">
                    <div class="accordion-body">
                        <p class="text-muted">{{ $agm->description }}</p>
                        <hr>
                        @if(in_array($agm->agendas[0]->id, $votes->pluck('agenda_id')->toArray()))
                        <h4 align="center" class="mt-3">Already Voted!</h4>
                        @else
                        <form method="POST" action="{{ route('votes.store') }}" class="mt-3 vote-form">
                            @csrf
                            @foreach($agm->agendas as $agenda)
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    {{ $agenda->title }}
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        @foreach(explode("_", $agenda->voting_type) as $type)
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-secondary w-100 vote-option-btn" data-agenda="{{ $agenda->id }}" data-type="{{ $type }}">{{ $voteTypes[$type] }}</button>
                                        </div>
                                        @endforeach
                                        <input type="hidden" name="vote_value[{{ $agenda->id }}]" class="votes-input" data-agenda="{{ $agenda->id }}">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
                            @endforeach

                            <input type="hidden" name="agm_id" value="{{ $agm->id }}">
                            <input type="hidden" name="votes_cast" value="{{ $user->sharesForCompany($agm->company_id)->shares_owned }}">
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success w-100">DONE VOTING</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // For each agenda row, handle button selection
        document.querySelectorAll('.accordion-body').forEach(function(body) {
            body.querySelectorAll('.row').forEach(function(row) {
                let btns = row.querySelectorAll('.vote-option-btn');
                btns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        btns.forEach(b => b.classList.remove('btn-success'));
                        btns.forEach(b => b.classList.add('btn-secondary'));
                        btn.classList.remove('btn-secondary');
                        btn.classList.add('btn-success');
                        btns.forEach(b => b.setAttribute('aria-pressed', 'false'));
                        btn.setAttribute('aria-pressed', 'true');
                    });
                });
            });
        });

        // On button click, set the hidden input for that agenda (one input per agenda)
        document.querySelectorAll('.vote-option-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var agendaId = btn.dataset.agenda;
                var type = btn.dataset.type;
                // Find the hidden input for this agenda in the same form
                var form = btn.closest('form');
                var input = form.querySelector('input.votes-input[data-agenda="' + agendaId + '"]');
                if (input) {
                    input.value = type;
                }
            });
        });
        // On form submit, check all votes are selected and confirm with user
        document.querySelectorAll('.vote-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                var allFilled = true;
                var missing = [];
                form.querySelectorAll('input.votes-input').forEach(function(input) {
                    if (!input.value) {
                        allFilled = false;
                        var agendaLabel = input.closest('.row.mb-2')?.querySelector('.col-lg-6')?.innerText?.trim() || 'an agenda';
                        missing.push(agendaLabel);
                    }
                });
                if (!allFilled) {
                    e.preventDefault();
                    alert('Please vote on all agenda items before submitting.');
                    return;
                }
                if (!confirm('Are you sure you are done voting? You will not be able to change your votes after submission.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush