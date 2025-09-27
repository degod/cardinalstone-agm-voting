@extends('layout.master')
@section('title', 'Company Edit')

@section('body_content')
<h3>Company Edit</h3>
<p>Updating {{ $company->name }} Details</p>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $company->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="registration_number" class="form-label">Registration Number</label>
                <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ old('registration_number', $company->registration_number) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Company</button>
        </form>
    </div>
</div>
@endsection