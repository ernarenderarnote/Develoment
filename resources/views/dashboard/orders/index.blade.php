@extends('layouts.app')

@section('title')
    @lang('labels.orders')
@stop

@section('bodyClasses', 'page')

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="col-xs-12">
            <div class="jumbotron jumbotron-fluid bg-light orders-stats">
               <div class="row">
                    <div class="col-md-3">
                        <h1>@price($stats['ordersSumToday'])</h1>
                        <h3>
                            @choice('labels.n_orders', $stats['ordersToday'])
                            <strong>@lang('labels.today')</strong>
                        </h3>
                    </div>
                    <div class="col-md-3">
                        <h1>@price($stats['ordersSumLast7Days'])</h1>
                        <h3>
                            @choice('labels.n_orders', $stats['ordersLast7Days'])
                            <strong>@choice('labels.last_n_days', 7)</strong>
                        </h3>
                    </div>
                    <div class="col-md-3">
                            <h1>@price($stats['ordersSumLast28Days'])</h1>
                            <h3>
                                @choice('labels.n_orders', $stats['ordersLast28Days'])
                                <strong>@choice('labels.last_n_days', 28)</strong>
                            </h3>
                    </div>
                    <div class="col-md-3">
                        <h1>@price($stats['ordersProfitLast28Days'])</h1>
                        <h3>
                            @lang('labels.profit') <strong>@choice('labels.last_n_days', 28)</strong>
                        </h3>
                    </div>
                  
                    <div class="btn btn-link js-popover pos-a t r mt-10 mr-10"
                        data-toggle="popover"
                        data-trigger="hover"
                        data-content="@lang('labels.calculated_without_direct_orders')">
                        <i class="fa fa-info-circle"></i>
                    </div>
               </div>
            </div>
        </div>

        <div class="col-md-12 ptb-20">
            <h4 class="mt-0">@lang('labels.orders')</h4>

            {!! BootForm::open([])
                ->action('')
                ->addClass('row')
                ->get() !!}

                {!! BootForm::bind(\Input::all()) !!}

                <div class="col-md-3">
                    {!! BootForm::text(trans('labels.search'), 'search')
                        ->placeholder(trans('labels.search'))
                        ->hideLabel() !!}
                </div>
                <div class="col-md-3">
                    {!! BootForm::select(trans('labels.all_statuses'), 'status')
                        ->options(
                            ['' => trans('labels.all_statuses')]
                            + \App\Models\Order::listStatuses()
                        )
                        ->hideLabel() !!}
                </div>
                <div class="col-md-3">
                    {!! BootForm::select(trans('labels.all_stores'), 'store')
                        ->options(
                            ['' => trans('labels.all_stores')]
                            + auth()->user()->stores->pluck('name', 'id')->toArray()
                        )
                        ->hideLabel() !!}
                </div>
                <div class="col-md-3">
                    <div class="pull-left w-150">
                        {!! BootForm::select(trans('labels.per_page'), 'per_page')
                            ->options([
                                10 => trans('labels.n_per_page', [
                                    'results' => 10
                                ]),
                                25 => trans('labels.n_per_page', [
                                    'results' => 25
                                ]),
                                50 => trans('labels.n_per_page', [
                                    'results' => 50
                                ]),
                                100 => trans('labels.n_per_page', [
                                    'results' => 100
                                ])
                            ])
                            ->attribute('class', 'form-control')
                            ->hideLabel() !!}
                    </div>

                    <div class="pull-right">
                        {!! BootForm::submit() !!}
                    </div>
                </div>
            {!! BootForm::close() !!}

            @if (!$orders->isEmpty())
                <div class="my-orders">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="w-75">@lang('labels.store')</th>
                                <th class="w-75">@lang('labels.order')</th>
                                <th class="w-100">@lang('labels.date')</th>
                                <th class="w-150">@lang('labels.from')</th>
                                <th>@lang('labels.status')</th>
                                <th class="150">@lang('labels.total')</th>
                                <th class="150">@lang('labels.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($orders))
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="#js-order-details-modal-{{ $order->id }}" data-toggle="modal">
                                                #{{ $order->id }}
                                                @if($order->store)
                                                    {{ $order->store->name }}
                                                @endif
                                            </a>
                                            @include('widgets.dashboard.order.order-details-modal', [
                                                'order' => $order
                                            ])
                                        </td>
                                        <td>
                                            <div>{{ $order->orderNumber() }}</div>
                                            @if ($order->isDirectOrder())
                                                <div>
                                                    <small class="badge badge-default">
                                                        @lang('labels.direct_order')
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @app_time_tag($order->createdAtTZ())
                                        </td>
                                        <td>
                                            {{ $order->clientName() }}
                                        </td>
                                        <td>
                                            @include('dashboard.orders.update.status')
                                            @include('dashboard.orders.update.action-required')
                                        </td>
                                        <td>
                                            @price($order->total())
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button
                                                    type="button"
                                                    class="btn btn-primary"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                        @lang('actions.actions')
                                                        <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if (Gate::allows('edit_variants', $order))
                                                        <li>
                                                            <a
                                                                class="color-primary"
                                                                href="{{ url('/dashboard/orders/'.$order->id.'/update') }}">
                                                                    @lang('actions.edit')
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        <a
                                                            class="color-primary"
                                                            href="{{ url('/dashboard/orders/'.$order->id.'/review') }}">
                                                            @if (Gate::allows('pay', $order))
                                                                @lang('actions.confirm_order')...
                                                            @else
                                                                @lang('actions.order_details')
                                                            @endif
                                                        </a>
                                                    </li>

                                                    @if (Gate::allows('refund', $order))
                                                        <li>
                                                            <a
                                                                href="#js-order-refund-modal-{{ $order->id }}"
                                                                data-toggle="modal"
                                                                class="color-warning"
                                                                title="@lang('actions.request_refund')?">
                                                                @lang('actions.request_refund')...
                                                            </a>
                                                        </li>
                                                    @elseif (Gate::allows('cancel', $order))
                                                        <li>
                                                            {!! BootForm::open([])->post()
                                                                ->action('/dashboard/orders/'.$order->id.'/cancel')
                                                                ->attribute('class', 'd-b') !!}

                                                               {!! BootForm::submit(trans('actions.cancel_order'))
                                                                    ->attribute('class', 'btn btn-link') !!}

                                                            {!! BootForm::close() !!}
                                                        </li>
                                                    @endif

                                                    @if ($order->isVendorOrder())
                                                        <li>
                                                            <a
                                                                target="_blank"
                                                                href="{{ url('/dashboard/orders/'.$order->id.'/view-shopify') }}">
                                                                    @lang('actions.view_shopify_order')
                                                                    <i class="fa fa-external-link"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>

                                            @include('widgets.dashboard.order.order-refund-modal', [
                                                'order' => $order
                                            ])
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="7">
                                        {!! $orders->render() !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="7">
                                        @lang('labels.nothing_found')
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    <h3 class="ta-c mtb-20">@lang('labels.you_have_no_orders_yet')</h3>
                </div>
            @endif

        </div>

    </div>
</div>


@stop
