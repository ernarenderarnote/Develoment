<?php

namespace App\Http\Middleware;

use Closure;
use Braintree_Configuration;

class PaymentsForTesters
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (
            auth()->check()
            && auth()->user()->isTester()
            && config('testing.payments')
        ) {
            Braintree_Configuration::environment('sandbox');
            Braintree_Configuration::merchantId(config('services.braintree.sandbox.merchant_id'));
            Braintree_Configuration::publicKey(config('services.braintree.sandbox.key'));
            Braintree_Configuration::privateKey(config('services.braintree.sandbox.secret'));
        }

        return $next($request);
    }
}
