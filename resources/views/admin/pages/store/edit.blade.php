@extends("admin.pages.default.edit")

@section("content")

<div class="row">
    <div class="col-xs-12 col-md-8 col-lg-6">
        <div class="box box-primary">
            <div class="box-body form-horizontal">

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.name')</label>
                    <div class="col-sm-10">{{ $formObject->model->name }}</div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.name')</label>
                    <div class="col-sm-10">
                        <a class="label label-default" href="{{ url('/admin/users/'.$formObject->model->user->id.'/edit') }}" target="_blank">
                            <i class="fa fa-user"></i>
                            {{ $formObject->model->user->email }}
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.status')</label>
                    <div class="col-sm-10">
                        @if ($formObject->model->isInSync())
                            @if ($formObject->model->shopifyWebhooksAreSetUp())
                                <span class="label label-success">
                                    @lang('labels.active')
                                </span>
                            @else
                                <span class="label label-warning">
                                    @lang('labels.pending')
                                </span>
                            @endif
                        @else
                            <span class="label label-success">
                                @lang('labels.active')
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.website')</label>
                    <div class="col-sm-10"><a target="_blank" href="{{ $formObject->model->website }}">{{ $formObject->model->website }}</a></div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.domain')</label>
                    <div class="col-sm-10">{{ $formObject->model->domain }}</div>
                </div>

                @if ($formObject->model->isInSync())
                    <div class="form-group">
                        <label class="col-sm-2 control-label">@lang('labels.shopify_domain')</label>
                        <div class="col-sm-10">{{ $formObject->model->provider_domain }}</div>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.type')</label>
                    <div class="col-sm-10">{{ $formObject->model->isInSync() ? trans('labels.shopify_store') : trans('labels.custom_store') }}</div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('labels.store_api')</label>
                    <div class="col-sm-10">
                        @if ($formObject->model->token)
                            <div>@lang('labels.store_api_enabled')</div>
                            <input class="form-control" value="{{ $formObject->model->token->token }}" readonly="readonly" />
                        @else
                            @lang('labels.store_api_disabled')
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                {!! $form !!}
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-6">

        @if ($formObject->model->isInSync())
            <div class="box box-default">
                <div class="box-header ta-c">
                    <h3 class="box-title">@lang('labels.shopify_webhooks')</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ url('/admin/stores/'.$formObject->model->id.'/recreate-webhooks') }}" class="btn btn-box-tool">
                            <i class="fa fa-refresh"></i>
                            @lang('labels.recreate_webhooks')
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    @if ($formObject->model->shopifyWebhooksAreSetUp())
                        <pre>{{ print_r($formObject->model->getWebhooks(), true) }}</pre>
                    @else
                        <div class="alert alert-warning">@lang('labels.webhooks_are_not_set_up')</div>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>


@stop
