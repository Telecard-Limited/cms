@extends('layouts.architect')

@section('title', 'Report - Complains')
@section('icon', 'lnr-database')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
@endpush

@section('content')

    <div class="accordion-wrapper" id="accordionExample">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <button type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="text-left m-0 p-0 btn btn-link btn-block collapsed">
                    <h5 class="m-0 p-0">Select Filters</h5>
                </button>
            </div>
            <div class="card-body collapse show" id="collapseOne" aria-labelledby="headingOne" data-parent="#accordionExample">
                <form method="post" id="submitReport" action="{{ route('report.complain.post') }}">

                    <input id="from_datetime" type="hidden" value="{{ old('from_datetime') }}" name="from_datetime">
                    <input id="to_datetime" type="hidden" value="{{ old('to_datetime') }}" name="to_datetime">

                    <div class="form-group row">
                        <label for="datetimes" class="col-form-label col-sm-2">Date Range</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="datetimes" type="text" name="datetimes" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id" class="col-form-label col-sm-2">Complain ID</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="id" type="text" name="id" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="order_id" class="col-form-label col-sm-2">Order ID</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="order_id" type="text" name="order_id" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="customer_name" class="col-form-label col-sm-2">Customer Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="customer_name" type="text" name="customer_name" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="outlet_id" class="col-form-label col-sm-2">Outlet</label>
                        <div class="col-sm-10">
                            <select class="multiselect-dropdown form-control" id="outlet_id" name="outlet_id[]" multiple>
                                @foreach(\App\Outlet::pluck('name', 'id') as $index => $name)
                                    <option {{ old('outlet_id') == $index ? 'selected' : '' }} value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_id" class="col-form-label col-sm-2">Created By</label>
                        <div class="col-sm-10">
                            <select multiple class="multiselect-dropdown form-control" id="user_id" name="user_id[]">
                                @foreach(\App\User::pluck('name', 'id') as $index => $name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title" class="col-form-label col-sm-2">Complaint Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="paginate" class="col-form-label col-sm-2">Paginate</label>
                        <div class="col-sm-10">
                            <select class="singleselect-dropdown form-control" name="paginate" id="paginate">
                                @foreach(['10', '25', '50', '100', 'All'] as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-2">
                            <p class="font-weight-bold">
                                While exporting, select "All" from paginate tab to export all records.
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-2">
                            <button class="mb-2 mr-2 btn-icon btn btn-success">
                                <i class="pe-7s-tools btn-icon-wrapper"></i>Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body table-responsive">
            <table id="datatable" style="width: 100%;" class="table table-borderless table-striped">
                <thead>
                <tr>
                    <th>Complain#</th>
                    <th>Order#</th>
                    <th>Customer Name</th>
                    <th>Customer#</th>
                    <th>Outlet</th>
                    <th>Status</th>
                    <th>Category</th>
                    <th>Issue</th>
                    <th>Order Date</th>
                    <th>Informed To</th>
                    <th>Informed By</th>
                    <th>Description</th>
                    <th>Remarks</th>
                    <th>Platform</th>
                    <th>Created At</th>
                    <th>Created By</th>
                </tr>
                </thead>
                {{--<tbody>
                <tr>
                    <td colspan="10" class="font-size-lg font-weight-bold text-center">
                        Apply filters to display data.
                    </td>
                </tr>
                </tbody>--}}
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script>
        $(function() {
            let submit = document.getElementById('submitReport');
            let table = undefined;
            $.fn.dataTable.ext.errMode = 'none';
            let columns = [
                {'data' : 'id', 'title' : 'Complain #'},
                {'data' : 'order_id', 'title' : 'Order #'},
                {'data' : 'customer_name', 'title' : 'Customer Name'},
                {'data' : 'customer_number', 'title' : 'Customer #'},
                {'data' : 'outlet_id', 'title' : 'Outlet'},
                {'data' : 'ticket_status_id', 'title' : 'Status'},
                {'data' : 'category', 'title' : 'Category'},
                {'data' : 'issue_id', 'title' : 'Issue'},
                {'data' : 'order_datetime', 'title' : 'Order Date'},
                {'data' : 'informed_to', 'title' : 'Informed To'},
                {'data' : 'informed_by', 'title' : 'Informed By'},
                {'data' : 'desc', 'title' : 'Description'},
                {'data' : 'remarks', 'title' : 'Remarks'},
                {'data' : 'complain_source_id', 'title' : 'Platform'},
                {'data' : 'created_at', 'title' : 'Created At'},
                {'data' : 'user_id', 'title' : 'Created By'},
            ];

            let start = moment().startOf('day');
            let end = moment().endOf('day');
            $("#from_datetime").val(start.format("YYYY-MM-DD HH:mm"));
            $("#to_datetime").val(end.format("YYYY-MM-DD HH:mm"));

            var datetime = $('input[name="datetimes"]');
            datetime.daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                startDate: moment().startOf('day'),
                endDate: moment().endOf('day'),
                locale: {
                    format: 'YYYY-MM-DD HH:mm'
                }
            });

            datetime.on("apply.daterangepicker", (ev, picker) => {
                //console.log(ev, picker);
                $("#from_datetime").val(picker.startDate.format("YYYY-MM-DD HH:mm"));
                $("#to_datetime").val(picker.endDate.format("YYYY-MM-DD HH:mm"));
            });

            submit.onsubmit = (e) => {
                e.preventDefault();
                $("#collapseOne").removeClass('show');
                let paginate = document.getElementById("paginate").value;
                table = $("#datatable").DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{!! route('report.complain.post') !!}",
                        method: "post",
                        data: {
                            datetimes: document.getElementById('datetimes').value,
                            id: document.getElementById("id").value,
                            order_id: document.getElementById("order_id").value,
                            customer_name: document.getElementById("customer_name").value,
                            outlet_id: $("#outlet_id").val(),
                            user_id: $("#user_id").val(),
                            title: document.getElementById("title").value
                        }
                        /*success: (result, response, xhr) => {
                            console.log(result, response, xhr);
                        }*/
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'colvis', 'pageLength','copy', 'csv', 'excel', 'pdf', 'print',
                    ],
                    order: [[8, 'desc']],
                    columns: columns,
                    responsive: true,
                    pageLength: paginate === "All" ? -1 : parseInt(document.getElementById("paginate").value),
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
                });

                table.on("error.dt", (e, settings, techNote, message) => {
                    toastr.error(message);
                });
            }
        });
    </script>
@endpush


