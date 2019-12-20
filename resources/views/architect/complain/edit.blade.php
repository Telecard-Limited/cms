

@extends('layouts.architect')

@section('title', 'Complains')
@section('desc', 'Edit Complain / Ticket# ' . $complain->id)
@section('icon', 'lnr-bubble')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('complain.update', $complain->id) }}">
                @method('patch')
                @csrf

                <div class="form-group row">
                    <label for="outlet_id" class="col-form-label col-sm-2">Outlet <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control singleselect-dropdown @error('outlet_id') is-invalid @enderror" style="width: 100%; height: 100%" name="outlet_id" id="outlet_id" required disabled>
                            <option></option>
                            @foreach(\App\Outlet::pluck('name', 'id') as $index => $outlet)
                                <option {{ old('outlet_id')  == $index || $complain->outlet->id == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $outlet }}</option>
                            @endforeach
                        </select>
                        @error('outlet_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="issue_id" class="col-form-label col-sm-2">Complaint Type <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control multiselect-dropdown @error('issue_id') is-invalid @enderror" style="width: 100%;" name="issue_id[]" multiple id="issue_id" required disabled>
                            @foreach(\App\Issue::pluck('name', 'id') as $index => $issue)
                                <option value="{{ $index }}" {{ $complain->issues()->get()->contains('id', $index) ? 'selected' : '' }}>{{ $issue }}</option>
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
                    <label for="customer_name" class="col-form-label col-sm-2">Customer Name <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <input name="customer_name" type="text" id="customer_name" placeholder="Customer Name" value="{{ old('customer_name') ?: $complain->customer->name }}" class="form-control @error('customer_name') is-invalid @enderror" disabled>
                        @error('customer_name')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="order_id" class="col-form-label col-sm-2">Order Number</label>
                    <div class="col-sm-10">
                        <input name="order_id" type="text" id="order_id" placeholder="Order Number" value="{{ old('order_id') ?: $complain->order_id }}" class="form-control @error('order_id') is-invalid @enderror" disabled>
                        @error('order_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="order_datetime" class="col-form-label col-sm-2">Order Date/Time <sup style="color: red;">*</sup></label>
                    <div class="col-sm-10">
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input name="order_datetime" id="order_datetime" value="{{ old('order_datetime') ?? $complain->order_datetime }}" type="text" class="form-control datetimepicker-input @error('order_datetime') is-invalid @enderror" data-target="#datetimepicker1" required disabled />
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                        </div>
                        @error('order_datetime')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="promised_time" class="col-form-label col-sm-2">Promised Date/Time</label>
                    <div class="col-sm-10">
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input name="promised_time" id="promised_time" value="{{ old('promised_time') ?? $complain->promised_time }}" type="text" class="form-control datetimepicker-input @error('promised_time') is-invalid @enderror" data-target="#datetimepicker2" disabled />
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                        </div>
                        @error('promised_time')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="informed_to" class="col-form-label col-sm-2">Informed To</label>
                    <div class="col-sm-10">
                        <input name="informed_to" type="text" id="informed_to" placeholder="Informed To" value="{{ old('informed_to') ?? $complain->informed_to }}" class="form-control @error('informed_to') is-invalid @enderror" disabled>
                        @error('informed_to')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="informed_by" class="col-form-label col-sm-2">Informed By</label>
                    <div class="col-sm-10">
                        <input name="informed_by" type="text" id="informed_by" placeholder="Informed By" value="{{ old('informed_by') ?? $complain->informed_by }}" class="form-control @error('informed_by') is-invalid @enderror" disabled>
                        @error('informed_by')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ticket_status_id" class="col-form-label col-sm-2">Status <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control singleselect-dropdown @error('ticket_status_id') is-invalid @enderror" name="ticket_status_id" id="ticket_status_id" required style="width: 100%;">
                            <option></option>
                            @foreach(\App\TicketStatus::pluck('name', 'id') as $index => $status)
                                <option {{ old('ticket_status_id') == $index || $complain->ticket_status->id == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $status }}</option>
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
                    <label for="message_recipient_id" class="col-form-label col-sm-2">SMS Recipients</label>
                    <div class="col-sm-10">
                        <select class="form-control multiselect-dropdown @error('message_recipient_id') is-invalid @enderror" style="width: 100%; height: 100%" name="message_recipient_id[]" id="message_recipient_id" multiple disabled>
                            <option></option>
                            @foreach(\App\MessageRecipient::pluck('name', 'id') as $index => $messageRecipient)
                                <option {{ $complain->message_recipients->contains($index) || $complain->message_recipients->contains(old('message_recipient_id')) ? 'selected' : ''  }} value="{{ $index }}">{{ $messageRecipient }}</option>
                            @endforeach
                        </select>
                        @error('message_recipient_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="remarks" class="col-form-label col-sm-2">Remarks / Feedback</label>
                    <div class="col-sm-10">
                        <textarea rows="3" name="remarks" id="remarks" placeholder="Remarks / Feedback" class="form-control @error('desc') is-invalid @enderror">{{ old('remarks') ?: $complain->remarks }}</textarea>
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
                        <textarea rows="3" name="desc" id="desc" placeholder="Description" class="form-control @error('desc') is-invalid @enderror">{{ old('desc') ?: $complain->desc }}</textarea>
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
    <script>
        let url = "{{ route('search.customer') }}";
        $("#search_customer").select2({
            ajax: {
                url: url,
                dataType: "JSON",
                processResults: (data) => {
                    return {
                        results: $.map(data, (item) => {
                            return {
                                text: item.name,
                                id: item.id,
                                number: item.number
                            }
                        })
                    }
                }
            },
            theme: "bootstrap4",
            placeholder: "Search Customer by Name or Number",
            minimumInputLength: 4,
            templateSelection: (data, container) => {
                $(data.element).attr("data-number", data.number);
                return data.text;
            }
        });

        $("#search_customer").on("select2:select", (e) => {
            let elem = $("option:selected");
            let name = elem.text();
            let number = elem.data("number");
            let id = elem.val();

            $("#customer_name").val(name);
            $("#customer_number").val(number);
            $("#customer_id").val(id);
        });
    </script>
@endpush
