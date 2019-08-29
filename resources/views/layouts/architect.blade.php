<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Custom application developed for Dominos Pizza">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/styles/select2-bootstrap4.min.css') }}">
    <link href="{{ asset('assets/styles/main.css') }}" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
    @stack('styles')
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">

    @include('architect.components.app-header')

    <div style="display: none;">
        @include('architect.components.ui-theme')
    </div>


    <div class="app-main">

        @include('architect.components.sidebar')

        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="@yield('icon') icon-gradient bg-mean-fruit">
                                </i>
                            </div>
                            <div>
                                @yield('title')
                                <div class="page-title-subheading">@yield('desc')</div>
                            </div>
                        </div>

                        {{--@include('architect.components.app-actions')--}}

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">Success!</h5>
                                <hr>
                                <button type="button" data-dismiss="alert" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                                {{ session('status') }}
                                @if(session('links'))
                                    <hr>
                                    <ul>
                                        @foreach(session('links') as $link)
                                            <li>
                                                <a class="alert-link" href="{{ route('complain.show', ltrim($link, "0")) }}">{{ $link }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12">
                        @if(session('failure'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">Request Failed!</h5>
                                <hr>
                                <button type="button" data-dismiss="alert" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                                {{ session('failure') }}
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                @yield('content')
            </div>

            {{--@include('architect.components.footer')--}}

        </div>
        {{--<script src="http://maps.google.com/maps/api/js?sensor=true"></script>--}}
    </div>
</div>
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $('.multiselect-dropdown').select2({
        placeholder: 'Select An Option',
        theme: 'bootstrap4'
    });

    $('.singleselect-dropdown').select2({
        placeholder: 'Select An Option',
        theme: 'bootstrap4'
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@stack('scripts')
</body>
</html>
