<div
	class="modal fade"
	id="js-order-refund-modal-{{ $order->id }}"
	tabindex="-1"
	role="dialog"
	aria-labelledby="js-order-refund-modal-{{ $order->id }}-title"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-times"></i>
                </button>
                <div class="pull-left pr-30">
                    <h1>@lang('labels.refund_request_for_order') #{{ $order->orderNumber() }}</h1>
                    <h3>{{ $order->createdAt('M d Y, h:ia') }}</h3>
                </div>

                <div class="pull-left pl-30">
                    <h1>@price($order->total())</h1>
                    <h3>@choice('labels.n_items', count($order->variants))</h3>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        {!! BootForm::open([])->post()
                            ->action('/dashboard/orders/'.$order->id.'/refund')
                            ->attribute('class', 'd-b') !!}

                            <div class="col-xs-12">
                                {!! BootForm::textarea(trans('labels.refund_reason'), 'reason')
                                    ->addClass('form-control')
                                    ->required(true)!!}
                            </div>

                            <div class="col-xs-12">
                                <div class="pull-right">
                                    {!! BootForm::submit(trans('actions.request_refund'))
                                        ->addClass('class', 'btn btn-danger js-confirm')
                                        ->attribute('title', 'Refund?') !!}
                                </div>
                            </div>

                        {!! BootForm::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
