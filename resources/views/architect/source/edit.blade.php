

@extends('layouts.architect')

@section('title', 'Complain Source')
@section('desc', 'Edit complain source ' . $complainSource->name)
@section('icon', 'lnr-pie-chart')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('complainSource.update', $complainSource) }}">
                @csrf
                @method('patch')

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Name</label>
                    <div class="col-sm-10">
                        <input name="name" type="text" id="name" placeholder="Name" value="{{ old('name') ?? $complainSource->name }}" class="form-control @error('name') is-invalid @enderror">
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
                        <textarea id="description" name="description" class="form-control @error('status') is-invalid @enderror">{{ old('description') ?? $complainSource->description }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-cogs"></i> UPDATE</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
