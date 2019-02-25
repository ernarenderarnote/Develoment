<div class="row">
    <div class="col-xs-12">
        <div class="col-md-6 col-md-offset-3">
            <table class="table mt-15">
                <tbody>
                    <tr>
                        <td class="fw-b tt-u">@lang('labels.subtotal')</td>
                        <td class="ta-nd">
                            <span v-if="order && !isOnReviewScreen">
                                @{{ subtotal | currency }}
                            </span>
                            <span v-else>@price($order->subtotal())</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-b tt-u">@lang('labels.shipping_&_handling')</td>
                        <td class="ta-nd">
                            <span v-if="shippingPrice && !isOnReviewScreen">
                                @{{ shippingPrice | currency }}
                            </span>
                            <span v-else>@price($order->getShippingPrice())</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-b tt-u">@lang('labels.total')</td>
                        <td class="ta-nd">
                            <span v-if="order && !isOnReviewScreen">
                                @{{ total | currency }}
                            </span>
                            <span v-else>@price($order->total())</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
