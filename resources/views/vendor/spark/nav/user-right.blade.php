<!-- Right Side Of Navbar -->

@if (!auth()->user()->isAdmin() && !auth()->user()->isEmailConfirmed())
    <li
        class="js-popover"
        data-toggle="popover"
        data-trigger="hover"
        data-content="@lang('labels.email_is_not_confirmed').">

            <a href="#!" class="btn btn-link">
                <i class="fa fa-at fa-lg text-danger"></i>
                <sup>
                    <i class="fa fa-exclamation text-danger"></i>
                </sup>
            </a>
    </li>
@endif

<li
    v-if="!billable.paypal_email && !billable.card_last_four"
    class="js-popover"
    data-toggle="popover"
    data-trigger="hover"
    data-content="@lang('labels.need_payment_method_to_complete_order'). @lang('labels.click_to_set_payemnt_method', [ 'a' => '', '/a' => '' ])">
    <a
        href="{{ url('/settings#/payment-method') }}"
        target="_blank"
        class="btn btn-link">
            <i class="fa fa-credit-card fa-lg text-danger"></i>
            <sup>
                <i class="fa fa-exclamation text-danger"></i>
            </sup>
    </a>

</li>

@if (auth()->user()->isAdmin() && config('testing.payments'))
    <li
        class="js-popover"
        data-toggle="popover"
        data-trigger="hover"
        data-content="@lang('labels.payments_testing_enabled_for_testers')">

            <a href="#!" class="btn btn-link">
                <i class="fa fa-money fa-lg text-danger"></i>
                <sup>
                    <i class="fa fa-exclamation text-danger"></i>
                </sup>
            </a>
    </li>
@elseif (auth()->user()->isTester() && config('testing.payments'))
    <li
        class="js-popover"
        data-toggle="popover"
        data-trigger="hover"
        data-content="@lang('labels.payments_testing_enabled')">

            <a href="#!" class="btn btn-link">
                <i class="fa fa-money fa-lg text-danger"></i>
                <sup>
                    <i class="fa fa-exclamation text-danger"></i>
                </sup>
            </a>
    </li>
@endif
