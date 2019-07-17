@extends('layouts.architect')

@section('title', 'Complains')
@section('desc', 'Backend application complain management.')
@section('icon', 'pe-7s-comment')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.css"/>
@endpush

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="btn-actions-pane-right">
                <a class="btn-icon btn btn-secondary">
                    <i class="pe-7s-cloud-download btn-icon-wrapper"></i> Export
                </a>
                <a class="btn-icon btn btn-primary">
                    <i class="pe-7s-cloud-upload btn-icon-wrapper"></i> Import
                </a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table style="width: 100%;" id="dataTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Complain #</th>
                        <th>Customer</th>
                        <th>Customer #</th>
                        <th>Order #</th>
                        <th>Outlet / Dept.</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Issue</th>
                        <th>Created</th>
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
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.js"></script>
    <script>

        let url = "{!! route('complain.index') !!}";
        let table = $('#dataTable').DataTable({
            ajax: {
                type: "GET",
                url: url
            },
            orderCellsTop: true,
            fixedHeader: true,
            responsive: true,
            columns: [
                {'data' : 'id', 'title' : 'Complain #'},
                {'data' : 'customer_name', 'title' : 'Customer'},
                {'data' : 'customer_number', 'title' : 'Customer #'},
                {'data' : 'order_number', 'title' : 'Order #'},
                {'data' : 'type_name', 'title' : 'Outlet / Dept.'},
                {'data' : 'remarks', 'title' : 'Remarks'},
                {'data' : 'ticket_status_id', 'title' : 'Status'},
                {'data' : 'issue_id', 'title' : 'Issue'},
                {'data' : 'created_at', 'title' : 'Created'},
                {'data' : 'edit', 'title' : ''}
            ]
        });

        // Setup - add a text input to each footer cell
        $('#dataTable thead tr').clone(true).appendTo('#dataTable thead');
        $('#dataTable thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            console.log(this.className);
            if(this.className === "ignore sorting") {
                return;
            }
            $(this).html( '<input class="form-control form-control-sm" type="text" placeholder="Search '+title+'" />' );

            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );

    </script>
@endpush
