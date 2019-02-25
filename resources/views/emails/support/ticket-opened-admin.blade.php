@extends('beautymail::templates.sunny')

@section("content")

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $subject,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

		<p>{{ $supportRequest->subject }}</p>

        <div>{{ $supportRequest->text }}</div>

        @if ($supportRequest->order())
            <p>
                <strong>
                    @lang('labels.refund_reason')
                </strong>: {{ $supportRequest->order()->refund_status_comment }}
            </p>
        @endif

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        'title' => trans('actions.open_ticket'),
        'link' => url('/admin/support/'.$supportRequest->id.'/show')
    ])

@stop
