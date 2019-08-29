@extends('layouts.architect')

@section('title', 'Rating SMS Complains')
@section('desc', 'Backend application rating SMS complain management.')
@section('icon', 'fas fa-envelope')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
@endpush

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="" id="searchRating">
                <div class="form-group row">
                    <label for="query" class="col-form-label col-sm-2">Rating Complaint #</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="query" id="query">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 offset-2">
                        <button class="btn btn-alternate"><i class="fas fa-search-plus"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body table-responsive">
            <table style="width: 100%;" id="dataTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Informed To</th>
                    <th>Customer</th>
                    <th>Customer#</th>
                    <th>Outlet</th>
                    <th>Status</th>
                    <th>Issue(s)</th>
                    <th>Created</th>
                    <th>Created By</th>
                    <th class="ignore"></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script>

        let form = document.getElementById('searchRating');
        let url = "{!! route('rating.search') !!}";
        let data = null;

        form.onsubmit = function(e) {
            e.preventDefault();

            let value = document.getElementById('query').value;
            if(value.length === 0 && value === "") {
                toastr.error("Please enter query string in text box to search.");
                return;
            }


            let table = $('#dataTable').DataTable({
                ajax: {
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{!! csrf_token() !!}",
                        q: document.getElementById('query').value
                    }
                },
                order: [[8, 'desc']],
                destroy: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                columns: [
                    {'data' : 'id', 'title' : '#'},
                    {'data' : 'informed_to', 'title' : 'Informed To'},
                    {'data' : 'customer_name', 'title' : 'Customer'},
                    {'data' : 'customer_number', 'title' : 'Customer#'},
                    {'data' : 'outlet_id', 'title' : 'Outlet'},
                    {'data' : 'ticket_status_id', 'title' : 'Status'},
                    {'data' : 'issue_id', 'title' : 'Issue(s)'},
                    {'data' : 'created_at', 'title' : 'Created'},
                    {'data' : 'user_id', 'title' : 'Created By'},
                    {'data' : 'edit', 'title' : ''}
                ],
                responsive: true
            });

            table.on("error.dt", (e, settings, techNote, message) => {
                toastr.error(message);
            });
        };

        $.fn.dataTable.ext.errMode = 'none';


    </script>
@endpush
