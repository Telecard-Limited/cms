@extends('layouts.architect')

@section('title', 'Complains')
@section('desc', 'Backend application complain management.')
@section('icon', 'pe-7s-comment')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
@endpush

@section('content')

    <div class="main-card mb-3 card">
        {{--<div class="card-header">
            <div class="btn-actions-pane-right">
                <a href="{{ route('complain.export') }}" class="btn-icon btn btn-secondary">
                    <i class="pe-7s-cloud-download btn-icon-wrapper"></i> Export
                </a>
                <a href="javascript:void(0);" class="btn-icon btn btn-primary">
                    <i class="pe-7s-cloud-upload btn-icon-wrapper"></i> Import
                </a>
            </div>
        </div>--}}
        <div class="card-body table-responsive">
            <table style="width: 100%;" id="dataTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Complain #</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Customer #</th>
                        <th>Outlet</th>
                        <th>Title</th>
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

        let url = "{!! route('complain.index') !!}";
        let table = $('#dataTable').DataTable({
            createdRow: function( row, data, dataIndex ) {
                // console.log(row, data["class"], dataIndex);
                let __class = "table-danger";
                if (data["class"] === "1" || data[0] === 1) {
                    $(row).addClass(__class);

                }

            },
            ajax: {
                type: "GET",
                url: url

            },
            orderCellsTop: true,
            fixedHeader: false,
            order: [[8, 'desc']],
            dom: 'Bfrtip',
            buttons: [
                'colvis', 'pageLength','copy', 'csv', 'excel', 'pdf', 'print',
            ],
            stateSave: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            columns: [
                {'data' : 'id', 'title' : 'Complain #'},
                {'data' : 'order_id', 'title' : 'Order #'},
                {'data' : 'customer_name', 'title' : 'Customer'},
                {'data' : 'customer_number', 'title' : 'Customer #'},
                {'data' : 'outlet_id', 'title' : 'Outlet'},
                {'data' : 'title', 'title' : 'Title'},
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

        // Setup - add a text input to each footer cell
        /*$('#dataTable thead tr').clone(true).appendTo('#dataTable thead');
        $('#dataTable thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            //console.log(this.className);
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
        } );*/

    </script>
@endpush
