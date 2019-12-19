

@extends('layouts.architect')

@section('title', 'Complain Source')
@section('desc', 'Create source for complains.')
@section('icon', 'lnr-pie-chart')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('complainSource.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Name <sup style="color: red; font: bold;">*</sup></label>
                    <div class="col-sm-10">
                        <input name="name" type="text" id="name" placeholder="Name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="description" class="col-form-label col-sm-2">Description</label>
                    <div class="col-sm-10">
                        <input name="description" type="text" id="description" placeholder="Description" value="{{ old('description') }}" class="form-control @error('description') is-invalid @enderror">
                        @error('description')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-cogs"></i> SAVE</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
