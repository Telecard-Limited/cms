

@extends('layouts.architect')

@section('title', 'Rating SMS Complains')
@section('desc', 'Edit Rating / Ticket# ' . $rating->id)
@section('icon', 'pe-7s-comment')

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="post" action="{{ route('rating.update', $rating->id) }}">
                @method('patch')
                @csrf

                <input type="hidden" value="{{ $rating->customer->id }}" name="customer_id">

                <div class="form-group">
                    <div class="row">
                        <label for="customer_name" class="col-form-label col-sm-2">Customer Name <sup style="color:red;">*</sup></label>
                        <div class="col-sm-4">
                            <input name="customer_name" type="text" id="customer_name" placeholder="Customer Name" value="{{ old('customer_name') ?: $rating->customer->name }}" class="form-control @error('customer_name') is-invalid @enderror">
                            @error('customer_name')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>

                        <label for="customer_number" class="col-form-label col-sm-2">Customer Number <sup style="color:red;">*</sup></label>
                        <div class="col-sm-4">
                            <input name="customer_number" type="text" id="customer_number" placeholder="Customer Number" value="{{ old('customer_number') ?: $rating->customer->number }}" class="form-control @error('customer_number') is-invalid @enderror">
                            @error('customer_number')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="title" class="col-form-label col-sm-2">Complain Title</label>
                    <div class="col-sm-10">
                        <input name="title" type="text" id="title" placeholder="Title" value="{{ old('title') ?: $rating->title }}" class="form-control @error('title') is-invalid @enderror">
                        @error('title')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="order_id" class="col-form-label col-sm-2">Order Number</label>
                    <div class="col-sm-10">
                        <input name="order_id" type="text" id="order_id" placeholder="Order Number" value="{{ old('order_id') ?: $rating->order_id }}" class="form-control @error('order_id') is-invalid @enderror">
                        @error('order_id')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                </div>

                <div class="form-group row">
                    <label for="outlet_id" class="col-form-label col-sm-2">Outlet <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control singleselect-dropdown @error('outlet_id') is-invalid @enderror" style="width: 100%; height: 100%" name="outlet_id" id="outlet_id" required>
                            <option></option>
                            @foreach(\App\Outlet::pluck('name', 'id') as $index => $outlet)
                                <option {{ old('outlet_id')  == $index || $rating->outlet->id == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $outlet }}</option>
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
                    <label for="informed_to" class="col-form-label col-sm-2">Informed To <sup style="color: red;">*</sup></label>
                    <div class="col-sm-10">
                        <input rows="3" name="informed_to" id="informed_to" placeholder="Informed To" class="form-control @error('informed_to') is-invalid @enderror" value="{{ old('informed_to') ?: $rating->informed_to }}" required />
                        @error('informed_to')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="issue_id" class="col-form-label col-sm-2">Complaint Type <sup style="color:red;">*</sup></label>
                    <div class="col-sm-10">
                        <select class="form-control multiselect-dropdown @error('issue_id') is-invalid @enderror" style="width: 100%;" name="issue_id[]" multiple id="issue_id" required>
                            @foreach(\App\Issue::pluck('name', 'id') as $index => $issue)
                                <option value="{{ $index }}" {{ $rating->issues()->get()->contains('id', $index) ? 'selected' : '' }}>{{ $issue }}</option>
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
                        <select class="form-control singleselect-dropdown @error('ticket_status_id') is-invalid @enderror" name="ticket_status_id" id="ticket_status_id" required style="width: 100%;">
                            <option></option>
                            @foreach(\App\TicketStatus::pluck('name', 'id') as $index => $status)
                                <option {{ old('ticket_status_id') == $index || $rating->ticket_status->id == $index ? 'selected' : ''  }} value="{{ $index }}">{{ $status }}</option>
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
                        <textarea rows="3" name="remarks" id="remarks" placeholder="Remarks / Feedback" class="form-control @error('desc') is-invalid @enderror">{{ old('remarks') ?: $rating->remarks }}</textarea>
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
                        <textarea rows="3" name="desc" id="name" placeholder="Description" class="form-control @error('desc') is-invalid @enderror">{{ old('desc') ?: $rating->desc }}</textarea>
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
