@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h3>@lang('messages.your_product_variant_X_declined', [
            'name' => $variant->getFullTitle()
        ])</h3>
        
        <div>@lang('labels.moderation_status_comment')</div>
        <blockquote>
            {{ $variant->moderation_status_comment }}
        </blockquote>

    @include('beautymail::templates.sunny.contentEnd')

@stop
