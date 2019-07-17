

@extends('layouts.architect')

@section('title', 'Complains')
@section('desc', 'Create a new complain / ticket.')
@section('icon', 'pe-7s-comment')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('complain.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Customer Name<sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <input name="customer_name" type="text" id="customer_name" placeholder="Customer Name" value="{{ old('customer_name') }}" class="form-control @error('customer_name') is-invalid @enderror">
                        @error('customer_name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="name" class="col-form-label col-sm-2">Customer Number <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <input name="customer_number" type="text" id="customer_number" placeholder="Customer Number" value="{{ old('customer_number') }}" class="form-control @error('customer_number') is-invalid @enderror">
                        @error('customer_number')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="order_number" class="col-form-label col-sm-2">Order Number</label>
                    <div class="col-sm-10">
                        <input name="order_number" type="text" id="order_number" placeholder="Order Number" value="{{ old('order_number') }}" class="form-control @error('order_number') is-invalid @enderror">
                        @error('order_number')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="type_id" class="col-form-label col-sm-2">Outlet / Department <sup style="color:red;">*</sup></label>
                    <input name="type" id="type" type="hidden" value="" required>
                    <div class="col-sm-10">
                        <select class="select form-control @error('type_id') is-invalid @enderror" style="width: 100%; height: 100%" name="type_id" id="type_id" required>
                            <option value="" disabled="" selected>{{ __('Select Option') }}</option>
                            @foreach($groups as $index => $group)
                                <optgroup label="{{ $index }}">
                                    @foreach($group as $i => $item)
                                        <option value="{{ $i }}">{{ $item }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('type_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="issue_id" class="col-form-label col-sm-2">Complaint Type <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="select form-control @error('issue_id') is-invalid @enderror" style="width: 100%;" name="issue_id" id="issue_id" required>
                            <option value="" disabled="" selected>{{ __('Select Option') }}</option>
                            @foreach(\App\Issue::pluck('name', 'id') as $index => $issue)
                                <option value="{{ $index }}">{{ $issue }}</option>
                            @endforeach
                        </select>
                        @error('issue_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ticket_status_id" class="col-form-label col-sm-2">Status <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="select form-control @error('ticket_status_id') is-invalid @enderror" style="width: 100%;" name="ticket_status_id" id="ticket_status_id" required>
                            <option value="" disabled="" selected>{{ __('Select Option') }}</option>
                            @foreach(\App\TicketStatus::pluck('name', 'id') as $index => $status)
                                <option value="{{ $index }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('ticket_status_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="remarks" class="col-form-label col-sm-2">Remarks / Feedback</label>
                    <div class="col-sm-10">
                        <textarea rows="3" name="remarks" id="remarks" placeholder="Remarks / Feedback" class="form-control @error('desc') is-invalid @enderror">{{ old('remarks') }}</textarea>
                        @error('remarks')
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
            $('.select').select2({

            });
        });

        $('#type_id').on('select2:select', function (e) {
            let value = $('option:selected').parent("optgroup")[0].label;
            $('#type').val(value);
        })
    </script>
@endpush
