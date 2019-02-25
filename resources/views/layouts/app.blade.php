<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield("title") | Printable</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    <!-- CSS -->
    <link href="{{ mix(Spark::usesRightToLeftTheme() ? 'css/app-rtl.css' : 'css/app.css') }}" rel="stylesheet">
    
    

    <!-- Global Spark Object -->
    @include('js-localization::head')
    @yield('js-localization.head')
    <script>
        var _csrfToken = '{{ csrf_token() }}';
        window.App = {
            csrfToken: '{{ csrf_token() }}',
            data: {},
            models: {}
        };
        window.Spark = <?php echo json_encode(array_merge(
            Spark::scriptVariables(), []
        )); ?>;
    </script>
</head>
<body id="app-layout">
    <div id="spark-app" v-cloak>
        <input type="hidden" id="session" value="{{ session('shop_email') }}">
        <!-- Navigation -->
        @if (Auth::check())
            @include('spark::nav.user')
        @else
            @include('spark::nav.guest')
        @endif
        @include('layouts.parts.js')
        @include('spark::nav.secondary_nav')
        <!-- Main Content -->
        <div class="container">
            <main class="py-4">
                @yield('content')
            </main>
        </div>
        <!-- Application Level Modals -->
        @if (Auth::check())
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
        @endif
        @include('spark::nav.footer')
        @include('spark::nav.footer-meta')    
    </div>

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="/js/sweetalert.min.js"></script>
    
    <!-- Scripts -->
    @yield('scripts', '')
    
</body>
</html>
