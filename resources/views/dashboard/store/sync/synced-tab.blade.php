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
                        {{--@if ($product->providerImage())
                            <img class="img-responsive" src="{{ $product->providerImage() }}" alt="" />
                        @else
                            <img class="img-responsive" src="{{ url('/img/placeholders/placeholder-300x200.png') }}" alt="" />
                        @endif--}}
                        @if ($product->mockupPreview() && $product->mockupPreview()->url())
                            <img class="img-responsive" src="{{ $product->mockupPreview()->url('thumb') }}" alt="" />
                        @else
                            <img class="img-responsive" src="{{ url('/img/placeholders/placeholder-300x200.png') }}" alt="" />
                        @endif
                    </td>
                    <td colspan="1">
                        <div class="product-name fw-b">
                            @if ($product->isIgnored())
                                <div class="label label-default mr-10">
                                    @lang('labels.ignored')
                                </div>
                            @endif
                            {{ $product->name }}
                        </div>
                        @if ($product->provider_product_id)
                            <div class="external-id">
                                #{{ $product->provider_product_id }}
                            </div>
                        @endif
                    </td>
                    <td style="width: 255px;">
                        @include('widgets.dashboard.product.product-details', [
                            'product' => $product
                        ])
                        @if ($product->isActive())
                            <div class="sync-status ta-c">
                                <div class="synced d-ib">
                                    <div class="value">{{ count($product->variantsSynced) }}</div>
                                    <div class="caption">@lang('labels.synced')</div>
                                </div>
                                <div class="d-ib">|</div>
                                <div class="notSynced d-ib">
                                    <div class="value">{{ count($product->variantsNotSynced) }}</div>
                                    <div class="caption">@lang('labels.not_synced')</div>
                                </div>
                                <div class="d-ib">|</div>
                                <div class="ignored d-ib">
                                    <div class="value">{{ count($product->variantsIgnored) }}</div>
                                    <div class="caption">@lang('labels.ignored')</div>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td colspan="2" class="text-center" style="width: 70px;">
                        <a
                            href="{{ url('/dashboard/products/'.$product->id.'/edit') }}"
                            class="btn btn-primary btn-block">
                            @lang('actions.edit')
                        </a>
                    </td>
                    <td class="text-center" style="width: 125px;">
                        @if ($product->isActive())
                            {!! BootForm::open([])->post()
                                ->action('/dashboard/products/'.$product->id.'/ignore') !!}
                        
                               {!! BootForm::submit(trans('actions.ignore_product'))
                                    ->attribute('class', 'btn btn-default btn-block') !!}
                                
                            {!! BootForm::close() !!}
                        @elseif ($product->isIgnored())
                            {!! BootForm::open([])->post()
                                ->action('/dashboard/products/'.$product->id.'/unignore') !!}
                        
                               {!! BootForm::submit(trans('actions.unignore_product'))
                                    ->attribute('class', 'btn btn-default btn-block') !!}
                                
                            {!! BootForm::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="ta-c">
                    <div class="fs-i">@lang('labels.no_products_found').</div>
                </td>
            </tr>
        @endif 
    </tbody>
</table>
