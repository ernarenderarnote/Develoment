@extends('beautymail::templates.sunny')

@section("content")
    ;
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <p>@lang('labels.welcome'), {{ $user->email }}!</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.confirm_account'),
        'link' => url('/register/verify/'.$user->confirmation_code)
    ])

@stop
