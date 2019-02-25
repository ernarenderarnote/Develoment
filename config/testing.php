<?php

return [
    'orders' => (bool)getenv('APP_TEST_ORDERS'), // order totals for $0.01 for testers
    'payments' => (bool)getenv('APP_TEST_PAYMENTS'), // braintree sandbox for testers
];
