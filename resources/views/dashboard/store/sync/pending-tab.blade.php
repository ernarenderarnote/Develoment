<table class="table table-hover mt-20">
    <thead>
        <tr>
            <th colspan="2">@lang('labels.product')</th>
            <th colspan="4">@lang('labels.variants')</th>
        </tr>
    </thead>
    <tbody>
        @if (count($products))
            @foreach ($products as $product)
                <tr>
                    <td style="width: 86px;">
                        @if ($product->mockupPreview() && $product->mockupPreview()->url())
                            <img class="img-responsive" src="{{ $product->mockupPreview()->url('thumb') }}" alt="" />
                        @else
                            <img class="img-responsive" src="{{ url('/img/placeholders/placeholder-300x200.png') }}" alt="" />
                        @endif
                    </td>
                    <td colspan="1">
                        <div class="product-name fw-b">
                            {{ $product->name }}
                        </div>
                        <div>
                            @include('widgets.dashboard.product.moderation-status', [
                                'resource' => $product
                            ])
                        </div>
                    </td>
                    <td style="width: 255px;">
                        @include('widgets.dashboard.product.product-details', [
                            'product' => $product
                        ])
                    </td>
                    <td class="text-center" style="width: 70px;">
                        @if ($product->isNotApproved() || $product->isDeclined())
                            <button
                                type="button"
                                class="btn btn-default"
                                @click="showUpdateProductModal({{ $product->id }})">
                                @lang('actions.edit')
                            </button>
                        @endif
                        &nbsp;
                    </td>
                    <td class="text-center" style="width: 70px;">
                        {!! BootForm::open([])->post()
                            ->action('/dashboard/products/'.$product->id.'/delete') !!}
                    
                           {!! BootForm::submit(trans('actions.delete'))
                                ->attribute('class', 'btn btn-danger btn-block js-confirm')
                                ->attribute('data-text', trans('labels.confirm_product_deletion'))
                                ->attribute('data-confirm-button', trans('actions.delete'))
                                ->attribute('data-cancel-button', trans('actions.cancel')) !!}
                            
                        {!! BootForm::close() !!}
                    </td>
                    <td class="text-center" style="width: 125px;">
                        @if ($product->isApproved() || $product->isAutoApproved())
                            @if ($product->isQueuedForSync())
                                <button
                                    disabled="disabled"
                                    type="button"
                                    class="btn btn-primary btn-block disabled">
                                    @lang('labels.queued_for_sync')
                                </button>
                            @else
                                {!! BootForm::open([])->post()
                                    ->action('/dashboard/products/'.$product->id.'/push') !!}
                            
                                   {!! BootForm::submit(trans('actions.push_to_store'))
                                        ->attribute('class', 'btn btn-primary btn-block') !!}
                                    
                                {!! BootForm::close() !!}
                            @endif
                        @elseif ($product->isNotApproved())
                            {!! BootForm::open([])->post()
                                ->action('/dashboard/products/'.$product->id.'/send-to-moderation') !!}
                        
                               {!! BootForm::submit(trans('actions.send_on_moderation'))
                                    ->attribute('class', 'btn btn-primary btn-block') !!}
                                
                            {!! BootForm::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="ta-c">
                    <div class="fs-i">@lang('labels.no_pending_products_in_this_store').</div>
                </td>
            </tr>
        @endif 
    </tbody>
</table>
