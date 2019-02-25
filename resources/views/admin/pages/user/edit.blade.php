@extends("admin.pages.default.edit")

@section("content")

<div class="row">
    <div class="col-xs-12 col-md-8 col-lg-6">
        <div class="box box-primary">
            <div class="box-body">
                {!! $form !!}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-6">

        <div class="box box-default">
            <div class="box-header ta-c">
                <h3 class="box-title">@lang('labels.price_modifiers')</h3>
            </div>
            <div class="box-body">
                <price-modifiers :is-on-user-page="true"></price-modifiers>
            </div>
        </div>

    </div>
</div>


@stop

@section('scripts')
    <script>
        App.data.User = {!! json_encode($formObject->model->transformBrief()) !!};
        App.data.ProductModelTemplates = {!! json_encode($productModelTemplates) !!};
        App.data.PriceModifiers = {!! json_encode($priceModifiers) !!};
    </script>
@append
