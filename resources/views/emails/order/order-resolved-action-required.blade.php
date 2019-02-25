@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        @lang('messages.order_can_be_completed', [
            'order' => $order->id
        ])

    @include('beautymail::templates.sunny.contentEnd')
    
    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.open_order'),
        'link' => url('/dashboard/orders/'.$order->id.'/update')
    ])

@stop
