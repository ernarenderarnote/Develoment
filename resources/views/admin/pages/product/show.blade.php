@extends("admin.layouts.admin-layout")

@section("title")
    @if (!empty($subtitle))
        {{ $subtitle }} -
    @endif
    {{ $title }}
@stop

@section("bodyClasses", "page")

@section("content")

<admin-product-show inline-template="true">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-title">
                        @lang('labels.details')
                    </div>
                </div>
                <div class="box-body">

                    <strong>
                        @lang('labels.user')
                    </strong>
                    <p class="text-muted">
                        <span class="label label-default">{{ $model->user ? $model->user->email : '' }}</span>
                    </p>

                    <hr /><!-- separator -->

                    <strong>
                        @lang('labels.product')
                    </strong>
                    <p>
                        <span class="label label-default">
                            {{ $model->name }}
                        </span>
                    </p>
                    <p class="text-muted">
                        <span class="label label-default">
                            @if ($model->template())
                                <a href="{{ url('/admin/product-models/'.$model->template()->id.'/edit') }}">
                                    {{ $model->template()->name }}
                                </a>
                            @else
                                @lang('labels.product_model_template_missed')
                            @endif
                        </span>
                    </p>

                    <hr /><!-- separator -->

                    <strong>
                        @lang('labels.status')
                    </strong>
                    <p class="text-muted">
                        {{ $model->getStatusName() }}
                    </p>
                    <hr /><!-- separator -->

                    <strong>
                        @lang('labels.updated_created')
                    </strong>
                    <p class="text-muted">
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
                    </p>

                </div>
                <div class="box-footer">
                    @if (!$model->isApproved())
                        {!! BootForm::open([])->post()->addClass('d-ib')
                            ->action('/admin/products/'.$model->id.'/approve') !!}

                           {!! BootForm::submit(trans('actions.approve'))
                                ->attribute('class', 'btn btn-success')
                                ->attribute('v-if', '!declineSelected') !!}

                        {!! BootForm::close() !!}
                    @endif

                    @if (!$model->isDeclined())
                        {!! BootForm::open([])->post()->addClass('d-ib')
                            ->action('/admin/products/'.$model->id.'/decline') !!}

                            {!! BootForm::button(trans('actions.decline').'...')
                                ->attribute('@click', 'declineSelected=true')
                                ->attribute('type', 'button')
                                ->attribute('class', 'btn btn-warning')
                                ->attribute('v-if', '!declineSelected') !!}

                            <div v-if="declineSelected">
                                {!! BootForm::textarea(trans('labels.moderation_status_comment'), 'comment')
                                    ->required(true)
                                    ->attribute('class', 'form-control d-b res-v') !!}

                                <div>
                                    {!! BootForm::button(trans('actions.cancel'))
                                        ->attribute('@click', 'declineSelected=false')
                                        ->attribute('type', 'button')
                                        ->attribute('class', 'btn btn-default') !!}

                                    {!! BootForm::submit(trans('actions.decline'))
                                        ->attribute('class', 'btn btn-warning') !!}
                                </div>
                            </div>

                        {!! BootForm::close() !!}
                    @endif
                </div>
            </div>

            @if($model->template() && $model->template()->category->isHeadwear())
                <div class="box box-default">
                    <div class="box-header">
                        <div class="box-title">
                            @lang('labels.headwear_additional_info')
                        </div>
                    </div>
                    <div class="box-body">
                    {!! BootForm::open([])->post()
                        ->action('/admin/products/'.$model->id.'/save-meta') !!}

                        {!! BootForm::text(trans('labels.stitches'), 'stitches')
                            ->required(true)
                            ->value($model->getMeta('stitches'))
                            ->attribute('class', 'form-control d-b res-v') !!}

                        {!! BootForm::textarea(trans('labels.thread_colors'), 'thread_colors')
                            ->required(true)
                            ->value($model->getMeta('thread_colors'))
                            ->attribute('class', 'form-control d-b res-v') !!}

                            {!! BootForm::submit(trans('actions.save'))
                                ->attribute('class', 'btn btn-primary pull-right') !!}

                    {!! BootForm::close() !!}
                    </div>
                </div>
            @endif

            @if($model->template() && $model->template()->category->isPrepaid())
                <div class="box box-default">
                    <div class="box-header">
                        <div class="box-title">
                            @lang('labels.payment_status')
                        </div>
                    </div>
                    <div class="box-body">
                        @if ($model->firstSuccessfulPayment())
                            <div>
                                @lang('labels.paid'):
                                @price($model->firstSuccessfulPayment()->amount())
                            </div>
                            <div>
                                @lang('labels.time'):
                                @app_date($model->firstSuccessfulPayment()->createdAt(\DateTime::W3C))
                                @app_time($model->firstSuccessfulPayment()->createdAt(\DateTime::W3C))
                            </div>
                            <div>
                                @lang('labels.transaction_id'):
                                {{ $model->firstSuccessfulPayment()->transaction_id }}
                            </div>
                        @else
                            <div class="alert alert-warning">
                                @lang('labels.not_paid')
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="box box-default">
                <div class="box-header">
                    <div class="box-title">
                        @lang('labels.moderation_history')
                    </div>
                </div>
                <div class="box-body">

                    <ul class="timeline timeline-inverse">
                        <?php $lastDate = null; ?>
                        <?php $currentDate = null; ?>
                        @foreach($moderationStatusHistory as $i => $history)
                            <?php $currentDate = (new DateTime($history->updated_at, \App\Models\Base::getDateTimeZone()))->format("Y-m-d"); ?>

                            @if ($currentDate != $lastDate)
                                <li class="time-label">
                                    <span class="bg-green">
                                        @app_date($history->updated_at)
                                    </span>
                                </li>
                                <?php $lastDate = $currentDate; ?>
                            @endif
                            <li>
                                <i class="fa fa-refresh bg-green"></i>

                                <div class="timeline-item">
                                    <span class="time">
                                        <i class="fa fa-clock-o"></i>
                                        @app_time_tag($history->updated_at)
                                    </span>

                                    <h3 class="timeline-header no-border">
                                        @lang('labels.history_moderation_status', [
                                            'oldValue' => '<div class="label label-default">'.\App\Models\Product::moderationStatusName($history->oldValue()).'</div>',
                                            'newValue' => '<div class="label label-success">'.\App\Models\Product::moderationStatusName($history->newValue()).'</div>'
                                        ])

                                    </h3>

                                    @if (!empty($moderationCommentHistory[$i]))
                                        <small><blockquote>
                                            {{ $moderationCommentHistory[$i]->newValue() }}
                                        </blockquote></small>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-8">
            <div class="box box-info">
                <div class="box-header">
                    <div class="box-title">
                        @lang('labels.client_attachments')
                    </div>
                </div>
                <div class="box-body">
                    @include('admin.pages.product.show.client-files', [
                        'model' => $model
                    ])
                </div>
            </div>
        </div>
        {{-- <div class="col-xs-12 col-md-4">
            <div class="box box-info">
                <div class="box-header">
                    <div class="box-title">
                        @lang('labels.variant_design_assignments')
                    </div>
                </div>
                <div class="box-body">
                    @include('admin.pages.product.show.variants-assignments', [
                        'model' => $model
                    ])
                </div>
            </div>
        </div> --}}
    </div>
</admin-product-show>

@stop

@section('scripts')
    <script>
        App.data.ProductDesignerFileTypes = {!! json_encode(\App\Models\ProductDesignerFile::listTypes()) !!}
        App.data.CurrentProduct = {!! json_encode($model->transformFull()) !!};
    </script>
@append
