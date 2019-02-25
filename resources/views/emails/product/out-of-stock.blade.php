@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h3>@lang('labels.products_are_out_of_stock')</h3>
        <ul>
            @foreach($variants as $variant)
                <li>
                    <div><b>@lang('labels.store'):</b>
                        {{ $variant->product && $variant->product->store
                            ? $variant->product->store->name
                            : null
                        }}
                </div>
                    <div><b>@lang('labels.product'):</b>
                        {{ $variant->product
                            ? $variant->product->name
                            : null
                        }}
                    </div>
                    <div><b>@lang('labels.product_variant'):</b> #{{$variant->id}} {{ $variant->getFullTitle() }} </div>
                    <div class="product-detail-widget">
                        @include('widgets.dashboard.product.variant-details', [
                            'variant' => $variant
                        ])
                    </div>
                        
                    @if ($variant->product)
                        <div>
                            <a target="_blank" href="{{ $variant->product->providerProductEditUrl() }}">
                                @lang('actions.edit_in_shopify')
                            </a>
                        </div>
                    @endif
                    <hr />
                </li>    
            @endforeach
        </ul>

    @include('beautymail::templates.sunny.contentEnd')

@stop
