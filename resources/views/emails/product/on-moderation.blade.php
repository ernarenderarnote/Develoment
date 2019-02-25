@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h3>@lang('messages.new_product_sent_on_moderation')</h3>
        <h4>{{ $product->name }}</h4>
        
            @foreach($product->variants as $variant)
                <ul>
                    <li>
                        <h4>{{ $variant->getFullTitle() }}</h4>
                        <div class="product-detail-widget">
                            @include('widgets.dashboard.product.variant-details', [
                                'resource' => $variant
                            ])
                        </div>
                    </li>
                </ul>
            @endforeach
        

    @include('beautymail::templates.sunny.contentEnd')
    
    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.open_in_admin_dashboard'),
        'link' => url('/admin/products/'.$product->id.'/show')
    ])

@stop
