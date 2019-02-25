@extends("admin.layouts.admin-layout")

@section("title")
    @if (!empty($subtitle))
        {{ $subtitle }} -
    @endif
    {{ $title }}
@stop

@section("bodyClasses", "page")

@section("content")

<div class="row">
    <div class="col-xs-12 col-md-8 col-lg-6">

        @if (!$model->areAllShippingGroupsAssigned())
            <div class="alert alert-danger">
                @lang('labels.admin_action_required_shipping_group_assign_description')
            </div>
        @endif

        @if (!$model->areAllPricesSet())
            <div class="alert alert-danger">
                @lang('labels.admin_action_required_variant_price_missed_description')
            </div>
        @endif

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

        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="box-title">
                    @lang('labels.products')
                </div>
            </div>
            <div class="box-body">
                @include('widgets.dashboard.order.variants', [
                    'order' => $model,
                    'showFull' => true
                ])
            </div>
            <div class="box-footer clearfix">
                @include('admin.pages.order.actions')
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="box-title">
                    @lang('labels.order_history')
                </div>
                <div class="box-tools pull-right">
                    <b>@lang('labels.updated_created'):</b>
                    <time
                        datetime="{{ $model->updatedAtTZ() }}"
                        title="{{ $model->updatedAtTZ() }}"
                        data-format="">
                        {{ $model->updatedAtTZ() }}
                    </time>
                    /
                    <time
                        datetime="{{ $model->createdAtTZ() }}"
                        title="{{ $model->createdAtTZ() }}"
                        data-format="">
                        {{ $model->createdAtTZ() }}
                    </time>
                </div>
            </div>
            <div class="box-body">
                @include('widgets.dashboard.order.status-history', [
                    'order' => $model
                ])
            </div>
        </div>

    </div>
    <div class="col-xs-12 col-md-8 col-lg-6">

        <div class="box box-default">
            <div class="box-header with-border">
                <div class="box-title">
                    @lang('labels.store')
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2 ta-nd fw-b">
                        @lang('labels.name')
                    </div>
                    <div class="col-sm-10">
                        <a class="label label-default" href="{{ url('/admin/stores/'.$model->store->id.'/show') }}" target="_blank">
                            <i class="fa fa-shopping-cart"></i>
                            {{ $model->store->name }}

                            @if ($model->store->domain)
                                ( {{ $model->store->domain }} )
                            @endif
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 ta-nd fw-b">
                        @lang('labels.store_owner')
                    </div>
                    <div class="col-sm-10">
                        <a class="label label-default" href="{{ url('/admin/users/'.$model->user->id.'/edit') }}" target="_blank">
                            <i class="fa fa-user"></i>
                            {{ $model->user->email }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <div class="box-title">
                    @lang('labels.customer')
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-sm-2 ta-nd fw-b">
                        @lang('labels.name')
                    </div>
                    <div class="col-sm-10">
                        {{ $model->customerMeta('first_name') }}
                        {{ $model->customerMeta('last_name') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2 ta-nd fw-b">
                        @lang('labels.email')
                    </div>
                    <div class="col-sm-10">
                        {{ $model->customerMeta('email') }}
                    </div>
                </div>

                <hr /><!-- separator -->

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <b>
                            @lang('labels.shipping_address')
                        </b>
                        <p class="text-muted">
                            {!! nl2br($model->getShippingAddressMultiline()) !!}
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <strong>
                            @lang('labels.billing_address')
                        </strong>
                        <p class="text-muted">
                            {!! nl2br($model->getBillingAddressMultiline()) !!}
                        </p>
                    </div>
                </div>



            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <div class="box-title">
                    @lang('labels.shipping_groups')
                </div>
            </div>
            <div class="box-body">

                <ul class="list-group">
                    @foreach($model->variants as $variant)
                        <li class="list-group-item">
                            {{ $variant->getFullTitle() }}
                            @if ($variant->shippingGroup())
                                <a class="btn btn-xs btn-success pull-right" href="{{ url('/admin/orders/shipping/'.$variant->shippingGroup()->id.'/edit') }}">
                                    @lang('labels.edit_shipping_group') <b>{{ $variant->shippingGroup()->name }}</b>
                                </a>
                            @else
                                <div class="pull-right">
                                    <div class="label label-danger">@lang('labels.shipping_group_not_assigned')</div>
                                    <a class="btn btn-xs btn-warning" href="{{ url('/admin/orders/shipping/add?preselect_template_id='.($variant->model && $variant->model->template ? $variant->model->template->id : '')) }}">
                                        @lang('labels.add_shipping_group')
                                    </a>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>


@stop
