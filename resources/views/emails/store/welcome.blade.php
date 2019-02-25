@extends('beautymail::templates.sunny')

@section("content")
    
    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

        <p>What are the next steps?</p>

        <p>1. Sync your existing products through your Monetize Social dashboard by providing the print files for your products and their variations.</p>
        <p>2. You can also create completely new products with our product generator. Simply pick your product, upload your design, decide on pricing, and submit to your store.</p>
        <p>3. Watch the orders roll in.</p>
        <p>4. We'll ship them and send you a notification about it.</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.complete_sync'),
        'link' => url('/dashboard/store/'.$store->id.'/sync')
    ])

@stop
