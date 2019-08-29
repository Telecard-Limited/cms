

@extends('layouts.architect')

@section('title', 'SMS Recipients')
@section('desc', 'Create a new sms recipient list.')
@section('icon', 'lnr-book')



@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('messageRecipient.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Name</label>
                    <div class="col-sm-10">
                        <input name="name" type="text" id="name" placeholder="Name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="numbers" class="col-form-label col-sm-2">Numbers</label>
                    <div class="col-sm-10">
                        <textarea name="numbers" type="text" id="numbers" placeholder="Numbers" class="form-control @error('numbers') is-invalid @enderror">{{ old('numbers') }}</textarea>
                        @error('numbers')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                        <small id="numbers" class="form-text text-muted">
                            Please make sure to input comma separeted values (,) ex. 03001234567,03129876543
                        </small>
                    </div>


                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-cogs"></i> SUBMIT</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
