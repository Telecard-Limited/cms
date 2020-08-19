@extends('layouts.architect')

@section('title', 'Report - City Wise Graph')
@section('icon', 'metismenu-icon pe-7s-graph')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                <form id="myForm" method="post" action="{{ route('report.city-wise') }}">
                    @csrf

                    <input id="from_datetime" type="hidden" value="{{ old('from_datetime') }}" name="from_datetime">
                    <input id="to_datetime" type="hidden" value="{{ old('to_datetime') }}" name="to_datetime">

                    <div class="form-group row">
                        <label for="datetimes" class="col-form-label col-sm-2">Date Range</label>
                        <div class="col-sm-10">
                            <input value="{{ old('datetimes') }}" class="form-control" id="datetimes" type="text" name="datetimes" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="complain_type" class="col-form-label col-sm-2">Complain Type</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="complain_type" name="complain_type">
                                @foreach(\App\TicketStatus::query()->pluck('name', 'id') as $index => $status)
                                    <option {{ old('complain_type') === $index ? 'selected' : '' }} value="{{ $index }}">{{ $status }}</option>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {
            let route = '{!! route('report.city-wise') !!}';
            let myForm = document.getElementById('myForm');
            let start = moment().startOf('day');
            let end = moment().endOf('day');
            let myChart = null;
            $("#from_datetime").val(start.format("YYYY-MM-DD HH:mm"));
            $("#to_datetime").val(end.format("YYYY-MM-DD HH:mm"));

            let datetime = $('input[name="datetimes"]');
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

            myForm.onsubmit = (event) => {
                event.preventDefault();
                axios.post(route, {
                    from_datetime: $('#from_datetime').val(),
                    to_datetime: $('#to_datetime').val(),
                    ticket_status: $('#complain_type').val(),
                    _token: '{!! csrf_token() !!}'
                }).then(response => {
                    console.log(response)
                    if(myChart !== null) {
                        myChart.destroy();
                    }
                    let ctx = document.getElementById('myChart');
                    myChart = new Chart(ctx, {
                        type: 'pie',
                        data: response.data,
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Comparison'
                        }
                    });
                })
                .catch(error => console.log(error))
            }
        });
    </script>
@endpush


