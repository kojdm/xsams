<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'XSAMS') }}</title>

    <!-- Styles -->
    <script type="text/javascript" src="js/jquery/jquery-3.3.1.min.js"></script>
     
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
  
    <link rel="stylesheet" type="text/css" href="/datepicker/bootstrap-datepicker.min.css"/>
    <script type="text/javascript" src="/datepicker/bootstrap-datepicker.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css"/>
    <script type="text/javascript" src="/DataTables/datatables.min.js"></script>
</head>
<body>
    <div id="app">

        @include('layouts.navbar')

        <main class="py-4">
            <div class="container">
                @include('inc.messages')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
