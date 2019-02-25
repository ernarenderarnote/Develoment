@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h3>@lang('messages.your_product_X_declined', [
            'name' => $product->name
        ])</h3>
        
        <div>@lang('labels.moderation_status_comment')</div>
        <blockquote>
            {{ $product->moderation_status_comment }}
        </blockquote>

    @include('beautymail::templates.sunny.contentEnd')

@stop
