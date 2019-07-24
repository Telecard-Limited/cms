@extends('layouts.architect')

@section('title', 'Activity Logs')
@section('desc', 'Backend application activity logs.')
@section('icon', 'pe-7s-note2 icon-gradient bg-arielle-smile')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.css"/>
@endpush

@section('content')

    <div class="main-card mb-3 card">
        <div class="card-body table-responsive">
            {!! $html->table(['class' => 'table table-hover', 'style' => 'width: 100%;']) !!}
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.js"></script>
    {!! $html->scripts() !!}
@endpush
