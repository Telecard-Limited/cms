

@extends('layouts.architect')

@section('title', 'SETTINGS')
@section('icon', 'lnr-cog')

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

            <form method="post" action="{{ route('settings.update') }}">
                @method('patch')
                @csrf

                @foreach(\App\Setting::all() as $index => $setting)
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2" for="{{ $setting->key }}">{{ strtoupper($setting->key) }}</label>
                        <div class="col-sm-10">
                            <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ old($setting->key) ?? $setting->value }}" class="form-control @error($setting->key) is-invalid @enderror">
                        </div>
                        @error($setting->key)
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                @endforeach


                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-cog"></i> SUBMIT</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
