@extends('layouts.architect')

@section('title', 'Report - City Wise MTD Trend')
@section('icon', 'metismenu-icon pe-7s-graph')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker3.min.css') }}">
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
                <form id="myForm" method="post" action="{{ route('report.city-wise-mtd') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="datepicker" class="col-form-label col-sm-2">Date Range</label>
                        <div class="col-sm-10">
                            <div class="input-daterange input-group" id="datepicker">
                                <input id="start" type="text" class="input-sm form-control" name="start" />
                                <span class="input-group-addon ml-1 mr-1">to</span>
                                <input id="end" type="text" class="input-sm form-control" name="end" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="city" class="col-form-label col-sm-2">City</label>
                        <div class="col-sm-10">
                            <select multiple class="form-control" id="city" name="city[]"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-2">
                            <button type="submit" class="mb-2 mr-2 btn-icon btn btn-success">
                                <i class="pe-7s-tools btn-icon-wrapper"></i>Generate
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body table-responsive">
            <canvas id="myChart"></canvas>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="{{ asset('assets/scripts/cities.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function() {
            let route = '{!! route('report.city-wise-mtd') !!}';
            let myChart = null;

            $('#city').select2({
                placeholder: 'Select City',
                theme: 'bootstrap4',
                data: data,
                responsive: true
            });

            $('.input-daterange').datepicker({
                format: "mm/yyyy",
                minViewMode: 1
            });

            myForm.onsubmit = (event) => {
                event.preventDefault();
                axios.post(route, {
                    start: $('#start').val(),
                    end: $('#end').val(),
                    complain_type: $('#complain_type').val(),
                    city: $('#city').val(),
                    _token: '{!! csrf_token() !!}'
                }).then(response => {
                    console.log(response)
                    if(myChart !== null) {
                        myChart.destroy();
                    }
                    let ctx = document.getElementById('myChart');
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: response.data,
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'City Wise MTD Trend of Complaints'
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: true
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Month'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '# of Complains'
                                    }
                                }]
                            }
                        }
                    });
                }).catch(error => console.log(error))
            }
        });
    </script>
@endpush


