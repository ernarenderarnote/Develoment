@if ($order->isActionRequired())
    @foreach($order->action_required as $action_required)
        <div class="mt-5">
            <div class="alert alert-warning mb-5">
                @lang('messages.action_required_for_order_n', [
                    'order' => $order->id
                ]): {!! $order->getActionRequiredDescription($action_required) !!}
            </div>
        </div>
    @endforeach
@endif

<!-- fallback below -->
@if (
    !$order->isPlaced()
    && !$order->areAllShippingGroupsAssigned()
    && count($order->variants)
    && !$order->isActionRequired(App\Models\Order::ACTION_REQUIRED_SHIPPING_GROUP_ASSIGN)
)
    <div class="mt-5">
        <div class="alert alert-warning">
            @lang('labels.action_required_shipping_group_assign_description')
        </div>
    </div>
@endif

@if (
    !$order->isPlaced()
    && !$order->areAllPricesSet()
    && count($order->variants)
    && !$order->isActionRequired(App\Models\Order::ACTION_REQUIRED_VARIANT_PRICE_MISSED)
)
    <div class="mt-5">
        <div class="alert alert-warning">
            @lang('labels.action_required_variant_price_missed_description')
        </div>
    </div>
@endif

@if (
    !$order->isShippingMethodSelected()
    && $order->isDraft()
    && count($order->variants)
    && !$order->isActionRequired(App\Models\Order::ACTION_REQUIRED_SHIPPING_METHOD)
)
    <div class="mt-5">
        <div class="alert alert-warning">
            @lang('labels.check_order_to_set_shipping_method')
        </div>
    </div>
@endif
