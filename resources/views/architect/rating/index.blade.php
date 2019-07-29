@extends('layouts.architect')

@section('title', 'Rating SMS Complain')
@section('desc', 'Backend application rating SMS management.')
@section('icon', 'fas fa-envelope')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
@endpush

@section('content')

    <audio id="alert" src="{{ asset('sounds/bells call 3.mp3') }}"></audio>

    <div class="main-card mb-3 card">
        <div class="card-body table-responsive">
            <table style="width: 100%;" id="dataTable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Complain #</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Customer #</th>
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

        let url = "{!! route('rating.index') !!}";
        let audio = document.getElementById('alert');
        let table = $('#dataTable').DataTable({
            createdRow: function( row, data, dataIndex ) {
                // console.log(row, data["class"], dataIndex);
                if (data["class"] === "1" || data["class"] === 1) {
                    $(row).addClass("table-danger");
                    audio.play();
                }
            },
            ajax: {
                type: "GET",
                url: url
            },
            destroy: true,
            orderCellsTop: true,
            fixedHeader: false,
            order: [[7, 'desc']],
            dom: 'Bfrtip',
            buttons: [
                'colvis', 'pageLength','copy', 'csv', 'excel', 'pdf', 'print',
            ],
            stateSave: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columns: [
                {'data' : 'id', 'title' : 'Complain #'},
                {'data' : 'informed_to', 'title' : 'Informed To'},
                {'data' : 'customer_name', 'title' : 'Customer'},
                {'data' : 'customer_number', 'title' : 'Customer #'},
                {'data' : 'outlet_id', 'title' : 'Outlet'},
                {'data' : 'ticket_status_id', 'title' : 'Status'},
                {'data' : 'issue_id', 'title' : 'Issue(s)'},
                {'data' : 'created_at', 'title' : 'Created'},
                {'data' : 'user_id', 'title' : 'Created By'},
                {'data' : 'edit', 'title' : ''}
            ],
            responsive: true
        });

        $.fn.dataTable.ext.errMode = 'none';
        table.on("error.dt", (e, settings, techNote, message) => {
            toastr.error(message);
        });

        table.on("createdRow", function (e) {
            console.log(e);
        });

        setInterval(function () {
            table.ajax.reload(null, false);
        }, 5000);

    </script>
@endpush
