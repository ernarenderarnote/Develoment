<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>@yield("title") | @lang('labels.admin') | Printable</title>

<link href="/css/spark.css" rel="stylesheet" />
<link href="/css/admin.css" rel="stylesheet" />
{!! Rapyd::styles() !!}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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

@if (getenv('BUGSNAG_API_KEY'))
    <script src="//d2wy8f7a9ursnm.cloudfront.net/bugsnag-3.min.js"></script>
    <script>
        Bugsnag.apiKey = "{{ getenv('BUGSNAG_API_KEY') }}";
        Bugsnag.notifyReleaseStages = {!! json_encode(explode(',',getenv('BUGSNAG_NOTIFY_RELEASE_STAGES'))) !!};
        
        @if (auth()->user())
            Bugsnag.user = {!! json_encode(auth()->user()->transformBrief()) !!};
        @endif
    </script>
@endif
    
<!-- Styles -->
@yield('styles', '')

@include('layouts.parts.js')

<!-- Scripts -->
@yield('scripts', '')
