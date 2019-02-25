@extends('layouts.app')

@section('title')
    @lang('labels.print_files')
@stop

@section('bodyClasses', 'page')

@section('content')
   
    <div class="row">
        <div class="col-md-12">
            
            <ul class="nav nav-tabs" role="tablist">
                <li
                    role="presentation"
                    class="active">
                    <a href="#tab-prints" role="tab" data-toggle="tab">
                        @lang('actions.print_files')
                    </a>
                </li>
                <li
                    role="presentation"
                    class="">
                    <a href="#tab-sources" role="tab" data-toggle="tab">
                        @lang('actions.source_files')
                    </a>
                </li>
            </ul>
        
            <!-- Tab panes -->
            <div class="tab-content mt-15">
            
                <!-- tab -->
                <div
                    role="tabpanel"
                    class="tab-pane active"
                    id="tab-prints">
                    @include('widgets.dashboard.library.library-grid', [
                        'tagName' => 'print-files-library'
                    ])
                </div>
                    
                <!-- tab -->
                <div
                    role="tabpanel"
                    class="tab-pane"
                    id="tab-sources">
                    @include('widgets.dashboard.library.library-grid', [
                        'tagName' => 'source-files-library'
                    ])
                </div>
            </div>
        
        </div>
    </div>

@stop
