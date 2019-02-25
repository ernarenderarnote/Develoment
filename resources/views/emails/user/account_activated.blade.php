@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',

    ])

    @include('beautymail::templates.sunny.contentStart')

        

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.start'),
        'link' => url('/')
    ])

@stop
