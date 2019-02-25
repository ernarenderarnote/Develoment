@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        @lang('messages.you_have_been_billed_for_amount', [
            'amount' => $amount
        ])

    @include('beautymail::templates.sunny.contentEnd')

@stop
