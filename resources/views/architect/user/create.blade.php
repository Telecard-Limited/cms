

@extends('layouts.architect')

@section('title', 'Users')
@section('desc', 'Create a new user model.')
@section('icon', 'fas fa-user')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('users.store') }}">
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
                    <label for="username" class="col-form-label col-sm-2">Username</label>
                    <div class="col-sm-10">
                        <input name="username" type="text" id="username" placeholder="Username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror">
                        @error('username')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="email" class="col-form-label col-sm-2">E-Mail</label>
                    <div class="col-sm-10">
                        <input name="email" type="email" id="email" placeholder="E-Mail" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="password" class="col-form-label col-sm-2">Password</label>
                    <div class="col-sm-10">
                        <input name="password" type="password" id="password" placeholder="Password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-form-label col-sm-2">Confirm Password</label>
                    <div class="col-sm-10">
                        <input name="password_confirmation" type="password" id="password_confirmation" placeholder="Password" value="{{ old('confirm-password') }}" class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="role" class="col-form-label col-sm-2">Role</label>
                    <div class="col-sm-10">
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                            @foreach(\App\Role::all()->except(1) as $role)
                                <option value="{{ $role->id }}">{{ $role->desc }}</option>
                            @endforeach
                        </select>
                        @error('role')
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
