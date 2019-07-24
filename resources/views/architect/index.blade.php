@extends('layouts.architect')

@section('title', 'Dashboard')
@section('desc', 'Realtime statistics')
@section('icon', 'fas fa-rocket')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-xl-6">
                    @include('architect.widgets.dashboard', ['class' => 'bg-midnight-bloom', 'title' => 'Total Complains', 'desc' => 'All time complain received', 'url' => route('widget.data'), 'param' => 'complainCount'])
                </div>
                <div class="col-lg-6 col-xl-6">
                    @include('architect.widgets.dashboard', ['class' => 'bg-slick-carbon', 'title' => 'Rating Complains', 'desc' => 'All time rating complains received', 'url' => route('widget.data'), 'param' => 'ratingCount'])
                </div>
                <div class="col-lg-6 col-xl-6">
                    @include('architect.widgets.dashboard', ['class' => 'bg-premium-dark', 'title' => 'Open Complains', 'desc' => 'Complains containing Open status', 'url' => route('widget.data'), 'param' => 'openTickets'])
                </div>
                <div class="col-lg-6 col-xl-6">
                    @include('architect.widgets.dashboard', ['class' => 'bg-vicious-stance', 'title' => 'Closed Complains', 'desc' => 'Complains containing Closed status', 'url' => route('widget.data'), 'param' => 'closedTickets'])
                </div>
                <div class="col-lg-12 col-xl-12">
                    <canvas id="myChart" width="800" height="400"></canvas>
                    <p class="text-center">Daily Ticket distribution month wise</p>
                </div>
                <div class="col-lg-12 col-xl-12">
                    <canvas id="myChart2" width="800" height="400"></canvas>
                    <p class="text-center">Daily Rating ticket distribution month wise</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.com/libraries/Chart.js"></script>
    <script>
        var url = "{{ route('widget.data') }}";
        var ctx = document.getElementById('myChart');
        var ctx2 = document.getElementById('myChart2');
        var labels = [];
        var data = [];

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Daily Tickets',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(255, 206, 86, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "# of Complains"
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Day of Month"
                        }
                    }]
                }
            }
        });

        var myChart2 = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Daily Rating Tickets',
                    data: [],
                    borderWidth: 1,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "# of Complains"
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: "Day of Month"
                        }
                    }]
                }
            }
        });

        setInterval(() => {
            getData("complainCount", ".bg-midnight-bloom");
            getData("ratingCount", ".bg-slick-carbon");
            getData("openTickets", ".bg-premium-dark");
            getData("closedTickets", ".bg-vicious-stance");
            getLabels();
        }, 5000);

        function getData(param, _class) {
            axios.get(url, {
                params: {
                    param: param,
                    _token: "{{ csrf_token() }}"
                }
            })
                .then((response) => {
                    $(_class + " #data").html(response.data);
                })
                .catch((error) => {
                    toastr.error(error);
                })
        }

        function getLabels() {
            axios.get("{{ route('chart.labels') }}", {
                params: {
                    _token: "{{ csrf_token() }}"
                }
            })
                .then((response) => {
                    myChart.data.datasets[0].data = response.data.data;
                    myChart.data.labels = response.data.labels;
                    myChart2.data.datasets[0].data = response.data.data2;
                    myChart2.data.labels = response.data.labels;
                    myChart.update();
                    myChart2.update();
                })
                .catch((error) => {
                    toastr.error(error);
                })
        }
    </script>
@endpush
