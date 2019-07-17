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
    <link href="{{ asset('assets/styles/main.css') }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="{{ asset('css/toggleswitch.css') }}">--}}
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

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('failure'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                        {{ session('failure') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>

            {{--@include('architect.components.footer')--}}

        </div>
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    </div>
</div>
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/main.js') }}"></script>
@stack('scripts')
</body>
</html>
