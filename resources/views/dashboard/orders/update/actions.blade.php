@if (!isset($showDetails) || $showDetails)
<a
    class="btn btn-primary btn-block mt-10"
    href="{{ url('/dashboard/orders/'.$order->id.'/review') }}">
        @if (Gate::allows('pay', $order))
            @lang('actions.confirm_order')...
        @else
            @lang('actions.order_details')
        @endif
</a>
@endif
    
@if (Gate::allows('refund', $order))
    <a
        href="#js-order-refund-modal-{{ $order->id }}"
        data-toggle="modal"
        class="btn btn-danger btn-block mt-10"
        title="@lang('actions.request_refund')?">
        @lang('actions.request_refund')
    </a>
@elseif (Gate::allows('cancel', $order))
    {!! BootForm::open([])->post()
        ->action('/dashboard/orders/'.$order->id.'/cancel')
        ->attribute('class', 'd-b') !!}

       {!! BootForm::submit(trans('actions.cancel_order'))
            ->attribute('class', 'btn btn-warning btn-block mt-10') !!}
        
    {!! BootForm::close() !!}
@endif

@if ($order->isVendorOrder())
    <a
        target="_blank"
        class="btn btn-default btn-block mt-10"
        href="{{ url('/dashboard/orders/'.$order->id.'/view-shopify') }}">
            @lang('actions.view_shopify_order')
            <i class="fa fa-external-link"></i>
    </a>
@endif
