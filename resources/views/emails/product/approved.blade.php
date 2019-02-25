@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <h3>@lang('messages.your_product_X_approved', [
            'name' => $product->name
        ])</h3>
        

    @include('beautymail::templates.sunny.contentEnd')
    
    

@stop
