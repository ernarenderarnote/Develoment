@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        @lang('messages.automatic_payment_failed_for_order_n', [
            'order' => $payment->order->id
        ])

    @include('beautymail::templates.sunny.contentEnd')
    
    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.open_orders'),
        'link' => url('/dashboard/orders')
    ])

@stop
