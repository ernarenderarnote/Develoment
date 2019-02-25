@extends('beautymail::templates.sunny')

@section("content")

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        @if($plan)
        <p>PLAN: {{ $plan }}</p>
        @endif
        @if($store)
        <p>STORE: {{ $store }}</p>
        @endif
        <p>EMAIL: {{ $user->email }}</p>

    @include('beautymail::templates.sunny.contentEnd')

@stop
