<div class="row mb-20">
    <div class="col-xs-12">
        <a href="{{ url('/dashboard/orders') }}">
            <i class="fa fa-arrow-left"></i>
            @lang('actions.back_to_all_orders')
        </a>
    </div>
</div>
    
<div class="row mb-20">
    <div class="col-xs-12">
        <div class="order-header-widget">
            <div class="d-ib">
                {{ $order->orderNumber() }}
            </div>
            <div class="d-ib">|</div>
            <div class="d-ib">
                {{ $order->getStoreName() }}
            </div>
            <div class="d-ib">|</div>
            <div class="d-ib">
                @app_time_tag($order->createdAtTz())
            </div>
        </div>
    </div>
</div>
    
<ul class="nav nav-tabs" role="tablist">
    @can('edit_variants', $order)
        <li role="presentation" class="{{ Request::is('dashboard/orders/'.$order->id.'/update') ? 'active' : '' }}">
            <a href="{{ url('/dashboard/orders/'.$order->id.'/update') }}">
                @lang('actions.products')
            </a>
        </li>
    @endcan
    <li role="presentation" class="{{ Request::is('dashboard/orders/'.$order->id.'/shipping') ? 'active' : '' }}">
        <a href="{{ url('/dashboard/orders/'.$order->id.'/shipping') }}">
            @lang('actions.shipping')
        </a>
    </li>
    <li role="presentation" class="{{ Request::is('dashboard/orders/'.$order->id.'/review') ? 'active' : '' }}">
        <a href="{{ url('/dashboard/orders/'.$order->id.'/review') }}">
            @lang('actions.review_order')
        </a>
    </li>
        
    <li class="pull-right">
        @include('dashboard.orders.update.status')
    </li>
</ul>

@include('dashboard.orders.update.action-required')
    
@section('footer')
    <script>
        App.data.CurrentOrder = {!! json_encode($order->transformFull()) !!};
    </script>
@append
