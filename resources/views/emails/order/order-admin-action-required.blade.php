@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        @lang('messages.action_required_for_order_n', [
            'order' => $order->id
        ]): {{ $description }}

    @include('beautymail::templates.sunny.contentEnd')
    
    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.open_order'),
        'link' => url('/admin/orders/'.$order->id.'/edit')
    ])

@stop
