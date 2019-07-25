@extends('layouts.architect')

@section('title', 'Customer')
@section('desc', 'Edit customer: ' . $customer->name)
@section('icon', 'pe-7s-users')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">

            <form method="post" action="{{ route('customer.update', $customer->id) }}">
                @method('patch')
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Name</label>
                    <div class="col-sm-10">
                        <input name="name" type="text" id="name" placeholder="Name" value="{{ old('name') ?: $customer->name }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="number" class="col-form-label col-sm-2">Number</label>
                    <div class="col-sm-10">
                        <input name="number" type="text" id="number" placeholder="Number" value="{{ old('number') ?: $customer->number }}" class="form-control @error('number') is-invalid @enderror">
                        @error('number')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="active" class="col-form-label col-sm-2">Active</label>
                    <div class="col-sm-10">
                        <input class="form-control @error('active') is-invalid @enderror" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ old('active') || $customer->active ? 'checked' : '' }}>
                        @error('active')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
