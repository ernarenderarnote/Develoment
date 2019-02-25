<div class="row">
    <div class="col-md-12 mt-20">
        <h4 class="mt-0">@lang('labels.api_access')</h4>
    
        @if ($store->token)
            {!! BootForm::open([])
                ->action('/dashboard/store/'.$store->id.'/api-settings/disable')
                ->post()
                ->addClass('d-in') !!}
                
                {!! BootForm::text(
                        trans('labels.token'),
                        'token'
                    )
                    ->readonly()
                    ->value($store->token->token)
                !!}
                              
                <div>
                    {!!
                        BootForm::submit(trans('labels.disable_api_access'))
                            ->attribute('class', 'btn btn-danger')
                    !!}
                    
                    <a href="{{ url('/dashboard/store/'.$store->id.'/api-settings/regenerate-token') }}" class="d-ib btn btn-primary">
                        @lang('labels.regenerate_token')
                    </a>
                </div>
                
            {!! BootForm::close() !!}
        @else
            {!! BootForm::open([])
                ->action('/dashboard/store/'.$store->id.'/api-settings/enable')
                ->post() !!}
                              
                {!!
                    BootForm::submit(trans('labels.enable_api_access'))
                        ->attribute('class', 'btn btn-primary')
                !!}
                
            {!! BootForm::close() !!}
        @endif
    </div>
</div>
