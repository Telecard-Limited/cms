

@extends('layouts.architect')

@section('title', 'SMS Recipients')
@section('desc', 'Create a new SMS recipient department / outlet wise.')
@section('icon', 'pe-7s-mail')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('smsRecipient.store') }}">
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
                    <label for="name" class="col-form-label col-sm-2">Number</label>
                    <div class="col-sm-10">
                        <input name="number" type="text" id="name" placeholder="Number" value="{{ old('number') }}" class="form-control @error('number') is-invalid @enderror">
                        @error('number')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>


                <div class="form-group row">
                    <label for="desc" class="col-form-label col-sm-2">Description</label>
                    <div class="col-sm-10">
                        <textarea rows="3" name="desc" id="name" placeholder="Description" class="form-control @error('desc') is-invalid @enderror">{{ old('desc') }}</textarea>
                        @error('desc')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type_id" class="col-form-label col-sm-2">Outlet / Department</label>
                    <input name="type" id="type" type="hidden" value="" required>
                    <div class="col-sm-10">
                        <select class="form-control @error('type_id') is-invalid @enderror" style="width: 100%;" name="type_id" id="type_id" required>
                            <option></option>
                            @foreach($groups as $index => $group)
                                <optgroup label="{{ $index }}">
                                    @foreach($group as $i => $item)
                                        <option value="{{ $i }}">{{ $item }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#type_id').select2({
                placeholder: "Select outlet/department",
                allowClear: true
            });
        });

        $('#type_id').on('select2:select', function (e) {
            let value = $('option:selected').parent("optgroup")[0].label;
            $('#type').val(value);
        })
    </script>
@endpush
