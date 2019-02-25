<div class="row">
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.first_name'), 'first_name')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.first_name : order.billing_meta.first_name')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.last_name'), 'last_name')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.last_name : order.billing_meta.last_name')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.address1'), 'address1')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.address1 : order.billing_meta.address1')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.address2'), 'address2')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.address2 : order.billing_meta.address2')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.city'), 'city')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.city : order.billing_meta.city')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.province'), 'province')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.province : order.billing_meta.province')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::select(trans('labels.country'), 'country_code')
            ->options(
                collect(Geographer::getCountries()->toArray())
                    ->pluck('name', 'code')
                    ->toArray()
            )
            ->attribute('v-model', 'order.shipping_meta.country_code')
            ->attribute('@change', 'refreshShippingPrice')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.country_code : order.billing_meta.country_code')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.zip'), 'zip')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.zip : order.billing_meta.zip')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.company'), 'company')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.company : order.billing_meta.company')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
    <div class="col-xs-12 col-sm-6">
        {!! BootForm::text(trans('labels.phone'), 'phone')
            ->attribute(':value', 'addressSameAsShipping ? order.shipping_meta.phone : order.billing_meta.phone')
            ->attribute(':readonly', 'addressSameAsShipping && isOnReviewScreen')
            ->attribute(':disabled', '!editable')
        !!}
    </div>
</div>
