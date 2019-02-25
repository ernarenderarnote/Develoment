{!! BootForm::open([])
    ->attribute('id', 'js-update-store-form')
    ->addClass('mt-20')
    ->post() !!}

<div class="row">
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::bind($store) !!}

        {!! BootForm::text(trans('labels.name'), 'name') !!}

        @if ($store->website)
            {!! BootForm::text(trans('labels.website'), 'website')
                ->attribute('readonly', 'readonly') !!}
        @endif

        {!!
            BootForm::submit(trans('actions.save'))
                ->attribute('class', 'btn btn-danger')
        !!}
    </div>
    <div class="col-xs-12 col-sm-6">
        {{--<div class="control-group field-store">
            <label class="control-label" for="store-sticker">
                @lang('labels.upload_branding_sticker')
            </label>
            <p>
                @lang('labels.add_black_and_white_sticker')
                <button
                    type="button"
                    class="btn btn-link"
                    href="#js-branding-sticker-modal"
                    data-toggle="modal">
                        @lang('actions.find_out_more')
                </button>

                @include('widgets.dashboard.branding-sticker-modal')
            </p>
            <div class="controls">
                @include('widgets.fileinput', [
                    'file' => $store->sticker,
                    'name' => 'sticker',
                    'mode' => 'image'
                ])
            </div>
        </div>--}}
    </div>
</div>
{!! BootForm::close() !!}

<hr />

<div>
    <h4>@lang('labels.remove_store')</h4>

    <p>
        Removing your store will completely remove all content associated with it.
    </p>
    <p>
        There is no way back, please be careful with this option.
    </p>

    {!! BootForm::open([])
        ->action('/dashboard/store/'.$store->id.'/remove')
        ->attribute('id', 'js-remove-store-form')
        ->addClass('mt-20')
        ->post() !!}

       {!!
            BootForm::submit(trans('actions.remove').'...')
                ->data('text', trans('labels.confirm_store_deletion'))
                ->data('confirm-button', trans('actions.confirm_and_delete'))
                ->data('cancel-button', trans('actions.cancel'))
                ->attribute('class', 'btn btn-default js-confirm')
       !!}

    {!! BootForm::close() !!}
</div>
