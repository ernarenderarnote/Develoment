@extends("admin.layouts.admin-layout")

@section("title")
    @if (!empty($subtitle))
        {{ $subtitle }} - 
    @endif
    {{ $title }}
@stop

@section("bodyClasses", "page")

@section("content")
    
<admin-orders-shipping-edit inline-template="true">
    <div class="row">
        <div class="col-xs-12 col-md-6">
        
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-title">
                        @lang('labels.editing')
                    </div>
                </div>
                <div class="box-body">
                    {!! $form !!} 
                </div>
            </div>
                
        </div>
    </div>
</admin-orders-shipping-edit>

@stop

@section('scripts')
    <script>
        App.data.ProductModelTemplates = {!! json_encode($templates) !!};
        App.data.CurrentShippingSetting = {!! json_encode($model->transformFull()) !!};
    </script>
@append
