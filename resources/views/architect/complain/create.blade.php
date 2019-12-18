

@extends('layouts.architect')

@section('title', 'Complains')
@section('desc', 'Create a new complain / ticket.')
@section('icon', 'lnr-bubble')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@6.1.0/dist/css/autoComplete.min.css">
@endpush

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('complain.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="outlet_id" class="col-form-label col-sm-2">Outlet <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control singleselect-dropdown @error('outlet_id') is-invalid @enderror" style="width: 100%; height: 100%" name="outlet_id" id="outlet_id" required>
                            <option></option>
                            @foreach(\App\Outlet::pluck('name', 'id') as $index => $outlet)
                                <option {{ old('outlet_id') == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $outlet }}</option>
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
                    <label for="category_id" class="col-form-label col-sm-2">Complaint Category <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control multiselect-dropdown @error('category_id') is-invalid @enderror" style="width: 100%;" name="category_id" id="category_id" required>
                            <option></option>
                            @foreach(\App\Category::pluck('name', 'id') as $index => $issue)
                                <option {{ old('category_id') == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $issue }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="issue_id" class="col-form-label col-sm-2">Issue(s) <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control multiselect-dropdown @error('issue_id') is-invalid @enderror" style="width: 100%;" name="issue_id[]" multiple id="issue_id" required disabled>
                        </select>
                        @error('issue_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                {{--<div class="form-group row">
                    <label for="search_customer" class="col-form-label col-sm-2">Search Customer</label>
                    <div class="col-sm-10">
                        <select class="form-control" type="text" id="search_customer">
                            <option></option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 offset-sm-6">
                        <h4 class="font-weight-bold"><i>OR</i></h4>
                    </div>
                </div>--}}

                {{--<input type="hidden" name="customer_id" id="customer_id">--}}

                <div class="form-group">
                    <div class="row">
                        <label for="customer_name" class="col-form-label col-sm-2">Customer Name <sup style="color:red;">*</sup></label>
                        <div class="col-sm-10">
                            <input name="customer_name" type="text" id="customer_name" placeholder="Customer Name" value="{{ old('customer_name') }}" class="form-control @error('customer_name') is-invalid @enderror">
                            @error('customer_name')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>

                        {{--<label for="customer_number" class="col-form-label col-sm-2">Customer Number <sup style="color:red;">*</sup></label>
                        <div class="col-sm-4">
                            <input name="customer_number" type="text" id="customer_number" placeholder="Customer Number" value="{{ old('customer_number') }}" class="form-control @error('customer_number') is-invalid @enderror">
                            @error('customer_number')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>--}}
                    </div>
                </div>



                <div class="form-group row">
                    <label for="order_id" class="col-form-label col-sm-2">Order Number</label>
                    <div class="col-sm-10">
                        <input name="order_id" type="text" id="order_id" placeholder="Order Number" value="{{ old('order_id') }}" class="form-control @error('order_id') is-invalid @enderror">
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
                            <input name="order_datetime" id="order_datetime" value="{{ old('order_datetime') }}" type="text" class="form-control datetimepicker-input @error('order_datetime') is-invalid @enderror" data-target="#datetimepicker1" required />
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
                            <input name="promised_time" id="promised_time" value="{{ old('promised_time') }}" type="text" class="form-control datetimepicker-input @error('promised_time') is-invalid @enderror" data-target="#datetimepicker2" disabled />
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
                        <input name="informed_to" type="text" id="informed_to" placeholder="Informed To" value="{{ old('informed_to') }}" class="form-control @error('informed_to') is-invalid @enderror">
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
                        <input name="informed_by" type="text" id="informed_by" placeholder="Informed By" value="{{ old('informed_by') }}" class="form-control @error('informed_by') is-invalid @enderror">
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
                                <option {{ old('ticket_status_id') == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $status }}</option>
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
                        <select class="form-control multiselect-dropdown @error('message_recipient_id') is-invalid @enderror" style="width: 100%; height: 100%" name="message_recipient_id[]" id="message_recipient_id" multiple>
                            <option></option>
                            @foreach(\App\MessageRecipient::pluck('name', 'id') as $index => $messageRecipient)
                                <option {{ old('message_recipient_id') == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $messageRecipient }}</option>
                            @endforeach
                        </select>
                        @error('message_recipient_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                {{--<div class="form-group row">
                    <label for="desc" class="col-form-label col-sm-2">Description</label>
                    <div class="col-sm-10">
                        <textarea rows="3" name="desc" id="desc" placeholder="Description" class="form-control @error('desc') is-invalid @enderror">{{ old('desc') }}</textarea>
                        @error('desc')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>--}}

                <div id="insertBefore" class="form-group row">
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
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-cogs"></i> SUBMIT</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let url = "{{ route('search.customer') }}";
        let url2 = "{{ route('search.issue') }}";

        $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
            icons: {
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check-o',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            } });

        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker();



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

        $("#category_id").on("select2:select", (e) => {
            let elem = $("#category_id option:selected");
            let category = elem.val();
            $('#issue_id').empty().trigger("change");
            $.ajax({
                url: url2,
                dataType: 'JSON',
                data: {
                    _token: '{!! csrf_token() !!}',
                    category: category
                },
                success: function (data) {
                    let __data = $.map(data, (item) => {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    });
                    $("#issue_id").select2({
                        data: __data,
                        theme: "bootstrap4"
                    }).attr('disabled', false);
                }
            })
        });

        $("#issue_id").on("select2:select", (e) => {
            let name = $("#issue_id option:selected").text();
            let elem = $('#issue_id option:selected');
            let text = e.params.data.text;
            let id = e.params.data.id;
            // let __parent = $('#parent');
            let __form = $('#form');
            let __parent = $('<div id="desc_'+ id +'" class="form-group row"><label class="control-label col-sm-2">Description for ' +  text +'</label></div>');

            let newDiv = $('<div class="col-sm-10"><textarea class="form-control" name="desc_'+ id +'" placeholder="Description" rows="3"></textarea></div>');
            __parent.append(newDiv);
            // __form.append(__parent);
            __parent.insertBefore($('#insertBefore'));

            if(name === "Late Delivery") {
                $("#promised_time").attr("disabled", false).attr("required", true);
            }
        })
            .on("select2:unselect", (e) => {
                let text = e.params.data.text;
                let id = e.params.data.id;

                let elem = $('#desc_' + id);
                elem.remove();

                if(e.params.data.text === "Late Delivery") {
                    $("#promised_time").attr("disabled", true).attr("required", false);
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
