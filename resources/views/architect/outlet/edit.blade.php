

@extends('layouts.architect')

@section('title', 'Outlets')
@section('desc', 'Edit Outlet: ' . $outlet->name)
@section('icon', 'fas fa-shopping-bag')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">

            @if($errors)
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="post" action="{{ route('outlet.update', $outlet->id) }}">
                @method('patch')
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Name</label>
                    <div class="col-sm-10">
                        <input name="name" type="text" id="name" placeholder="Name" value="{{ old('name') ?: $outlet->name }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="active" class="col-form-label col-sm-2">Active</label>
                    <div class="col-sm-10">
                        <input id="active" name="active" class="form-control @error('active') is-invalid @enderror" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ old('active') || $outlet->active ? 'checked' : '' }}>
                        @error('active')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city" class="col-form-label col-sm-2">City</label>
                    <div class="col-sm-10">
                        <select name="city" type="text" id="city" class="form-control city-select @error('city') is-invalid @enderror" required>
                            <option></option>
                        </select>
                        @error('city')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="desc" class="col-form-label col-sm-2">Description</label>
                    <div class="col-sm-10">
                        <textarea rows="3" name="desc" id="name" placeholder="Description" class="form-control @error('desc') is-invalid @enderror">{{ old('desc') ?: $outlet->desc }}</textarea>
                        @error('desc')
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

@push('scripts')
    <script src="{{ asset('assets/scripts/cities.js') }}"></script>
    <script>
        var selected = '{{ old('city') ?: $outlet->city }}';

        $(document).ready(function () {
            $('.city-select').select2({
                placeholder: 'Select City',
                theme: 'bootstrap4',
                data: data,
                selected: selected
            });
            $('.city-select').val(selected).trigger("change");
        });

    </script>
@endpush
