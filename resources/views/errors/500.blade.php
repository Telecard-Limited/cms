<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>{{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Encode+Sans+Semi+Condensed:100,200,300,400" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/errors.css') }}">

</head>

<body>
<div id="error">
    <div id="box"></div>
    <h3>ERROR 500</h3>
    <h4>Internal Server Error</h4>
    <p>Things are a little <span>unstable</span> here</p>
    <p>I suggest come back later</p>
    <p>E-Mail us at <a href="mailto:fet@telecard.com.pk" target="_top">DevTeam</a> or <a href="{{ url("/") }}">Go Back</a></p>
</div>
</body>

</html>
