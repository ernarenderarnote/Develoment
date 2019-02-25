@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h3>@lang('messages.new_product_variants_sent_on_moderation')</h3>
        <ul>
            @foreach($variants as $variant)
                <li>
                    <h4>{{ $variant->getFullTitle() }}</h4>
                    <div class="product-detail-widget">
                        @include('widgets.dashboard.product.variant-details', [
                            'variant' => $variant
                        ])
                    </div>
                    <a href="{{ url('/admin/product-variants/'.$variant->id.'/show') }}">
                        @lang('actions.open_in_admin_dashboard')
                    </a>
                    <hr />
                </li>    
            @endforeach
        </ul>

    @include('beautymail::templates.sunny.contentEnd')

@stop
