<!DOCTYPE html>
<html lang="en">
<head>
    @include("admin.partials.meta")
</head>
<body class="@yield("bodyClasses") hold-transition skin-black sidebar-mini">
<div class="wrapper" id="spark-app">
    @include('spark::nav.admin')
    
    @include("admin.partials.left-nav")
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @if ( !empty($title) )
                    {{ $title }}
                    @if ( !empty($subtitle) )
                        <small>{{ $subtitle }}</small>
                    @endif
                @else
                    @yield('title')
                @endif
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('/admin') }}">
                        <i class="fa fa-dashboard"></i>
                        @lang('labels.admin')
                    </a>
                </li>
                    
                <li class="active">
                    @if ( !empty($title) )
                        {{ $title }}
                    @else
                        @yield('title')
                    @endif
                </li>
                @if ( !empty($subtitle) )
                    <li class="active"> 
                        {{ $subtitle }}
                    </li>
                @endif
            </ol>
        </section>
    
        <!-- Main content -->
        <section class="content">
            @include('flash::message')
        
            @include("admin.partials.content")
        </section>
    </div>
        
    @if (Auth::check())
        @section('footer')
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
        @append
    @endif
        
    @include('admin.partials.footer')
    @include('admin.partials.footer-meta')
    
    {{-- @include("admin.partials.right-menu") --}}
</div>
</body>
</html>
