<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">1
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>INSPINIA | Login 2</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

@yield('content')
<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
