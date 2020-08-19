@extends('layouts.architect')

@section('title', 'Report - MTD Comparison of Complaint Categories')
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
                <form id="myForm" method="post" action="{{ route('report.mtd-comparison') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="month1" class="col-form-label col-sm-2">Select Month</label>
                        <div class="col-sm-10">
                            <input value="{{ old('month1') ?? "2020-01" }}" class="form-control" id="month1" type="text" name="month1" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="month2" class="col-form-label col-sm-2">Select Month</label>
                        <div class="col-sm-10">
                            <input value="{{ old('month2') ?? "2020-02" }}" class="form-control" id="month2" type="text" name="month2" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="month3" class="col-form-label col-sm-2">Select Month</label>
                        <div class="col-sm-10">
                            <input value="{{ old('month3') ?? "2020-03" }}" class="form-control" id="month3" type="text" name="month3" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="complain_type" class="col-form-label col-sm-2">Complain Type</label>
                        <div class="col-sm-10">
                            <select multiple class="form-control" id="complain_type" name="complain_type[]">
                                @foreach(\App\Category::query()->pluck('name', 'id') as $index => $status)
                                    <option {{ old('complain_type') ? 'selected' : '' }} value="{{ $index }}">{{ $status }}</option>
                                @endforeach
                            </select>
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
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function() {
            let route = '{!! route('report.mtd-comparison') !!}';
            let myForm = document.getElementById('myForm');
            let start = moment().startOf('day');
            let end = moment().endOf('day');
            let myChart = null;

            $('#month1, #month2, #month3').datepicker({
                autoclose: true,
                minViewMode: 1,
                format: 'yyyy-mm'
            })

            myForm.onsubmit = (event) => {
                event.preventDefault();
                axios.post(route, {
                    month1: $('#month1').val(),
                    month2: $('#month2').val(),
                    month3: $('#month3').val(),
                    complain_type: $('#complain_type').val(),
                    _token: '{!! csrf_token() !!}'
                }).then(response => {
                    console.log(response)
                    if(myChart !== null) {
                        myChart.destroy();
                    }
                    let ctx = document.getElementById('myChart');
                    myChart = new Chart(ctx, {
                        type: 'bar',
                        data: response.data,
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                })
                    .catch(error => console.log(error))
            }
        });
    </script>
@endpush


