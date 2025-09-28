@extends('layout.master')
@section('title', 'AGM Edit')

@section('body_content')
<h3>Shareholder Edit</h3>
<p>Updating {{ $shareholder->user->name }} shares details</p>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('shareholders.update', $shareholder->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="user" class="form-label">Shareholder</label>
                <input type="text" class="form-control" id="user" readonly value="{{ $shareholder->user->name }}" required>
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Company</label>
                <input type="text" class="form-control" id="company" readonly value="{{ $shareholder->company->name }}" required>
            </div>
            <div class="mb-3">
                <label for="shares_owned" class="form-label">Number of Shares</label>
                <input type="number" class="form-control" id="shares_owned" name="shares_owned" value="{{ old('shares_owned', $shareholder->shares_owned) }}" min="1" required>
            </div>
            <div class="mb-3">
                <label for="share_certificate_number" class="form-label">Certificate Number</label>
                <input type="text" class="form-control" id="share_certificate_number" name="share_certificate_number" value="{{ old('share_certificate_number', $shareholder->share_certificate_number) }}">
            </div>
            <div class="mb-3">
                <label for="acquired_date" class="form-label">Acquisition Date</label>
                <input type="date" class="form-control" id="acquired_date" name="acquired_date" value="{{ old('acquired_date', \Carbon\Carbon::parse($shareholder->acquired_date)->format('Y-m-d') ) }}" min="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select class="form-select" id="is_active" name="is_active" required>
                    <option value=""> -- Select Status --</option>
                    @foreach($shareholderStatuses as $statusKey=>$statusValue)
                    <option value="{{ $statusKey }}" {{ $shareholder->is_active == $statusKey ? 'selected' : '' }}>{{ $statusValue }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update AGM</button>
        </form>
    </div>
</div>
@endsection