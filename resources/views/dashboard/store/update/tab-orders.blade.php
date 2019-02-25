{!! BootForm::open([])
    ->action('/dashboard/store/'.$store->id.'/save-settings/orders')
    ->post() !!}

    {!! BootForm::bind($store->settings) !!}
    
    <div class="col-md-12 mt-20 p-0">
        <h4 class="mt-0">@lang('labels.order_import_settings')</h4>
    </div>

    <div class="col-md-12 mt-20 p-0">
        {!! BootForm::radio(
                trans('labels.manually_confirm_imported_orders'),
                'auto_orders_confirm'
            )
            ->value(0)
        !!}
        <p>All orders from your ecommerce store will be imported as drafts. You can then confirm them to be fulfilled.</p>
            
        {!! BootForm::radio(
                trans('labels.automatically_confirm_orders_to_be_fulfilled'),
                'auto_orders_confirm'
            )
            ->value(1)
        !!}
        
        <p>All orders from your online store will automatically be fulfilled.</p>
    </div>
        
    <div class="col-md-12 mt-20 p-0">
        <h4 class="mt-0">@lang('labels.sync_as_you_go')</h4>
    </div>
        
    <div class="col-md-12 p-0">
    
        <div class="form-group">
            {!! BootForm::hidden('auto_push_products')
                ->value(0)
            !!}
            {!! BootForm::checkbox(
                    trans('labels.auto_push_products'),
                    'auto_push_products'
                )
                ->value(1)
            !!}
        </div>
            
        {{-- 
            <div class="form-group">
                {!! BootForm::checkbox(
                        trans('labels.import_unsynced_items'),
                        'import_unsynced'
                    )
                    ->disabled()
                    ->value(1)
                !!}
                
                <p>Items that aren’t synced will be imported, and you’ll be able to sync them as they come in.</p>
                <p>This is ideal for new stores that have many designs and variants.</p>
                <p>If this option is checked, and you’ve selected automatic order fulfillment, then orders with unsynced items will be saved as drafts to be manually confirmed.</p>
            </div>
                
            <div class="form-group">
                {!! BootForm::checkbox(
                        trans('labels.notify_about_unsynced_orders'),
                        'notify_unsynced'
                    )
                    ->disabled()
                    ->value(1)
                !!}
                
                <p>Every time an order comes in with an unsynced item, you'll receive an email notification.</p>
            </div>
                
            <div class="form-group">
                {!! BootForm::checkbox(
                        trans('labels.automatic_stock_update'),
                        'auto_stock_update'
                    )
                    ->disabled()
                    ->value(1)
                !!}
                
                <p>Monetize Social will automatically update product status to “out of stock” for items that are discontinued or temporarily out of stock with our suppliers for over 24 hours. Product status will be updated again as soon as items are back in stock.</p>
            </div>
        --}}
    </div>
        
    <div class="col-md-12 mt-20 p-0">
        <h4 class="mt-0">@lang('labels.card_charge_limit')</h4>
    </div>
        
    <div class="col-md-12 p-0">
    
        <div class="form-group">
        
            {!! BootForm::checkbox(
                    trans('labels.card_charge_limit_enabled'),
                    'card_charge_limit_enabled'
                )
                ->attribute('v-model', 'settings.card_charge_limit_enabled')
                ->value(1)
            !!}
        </div>
            
        <div
            v-if="settings.card_charge_limit_enabled"
            class="form-group">
                <div class="d-ib">
                    {!! BootForm::text(
                            trans('labels.card_charge_limit_amount'),
                            'card_charge_limit_amount'
                        )
                        ->addClass('mxw-100')
                    !!}
                </div>
                
                <div class="d-ib va-m">
                    <b>@lang('labels.charges_amount')</b>: $@{{ settings.card_charge_charges_amount }}
                </div>
                <a
                    v-if="settings.card_charge_charges_amount > 0"
                    class="btn btn-link color-warning va-m"
                    href="{{ url('/dashboard/store/'.$store->id.'/reset-charges') }}">
                    <i class="fa fa-times"></i>
                        @lang('actions.reset')
                </a>
        </div>
    </div>    
        
    <div class="col-md-12 mt-20 p-0">
        {!!
            BootForm::submit(trans('actions.save'))
                ->attribute('class', 'btn btn-success')
        !!}
    </div>
    
{!! BootForm::close() !!}
