<?php

return [
    'cache' => [
        'ttl' => [
            'category' => [
                'get_on_frontend' => 60
            ],
            'product_model_template' => [
                'get_on_frontend' => 60
            ]
        ]
    ],
    'emails' => [
        'intervals' => [
            'order-product-admin-action-required' => 60
        ]
    ],
    'money' => [
        // will be included to the ProductModel price
        // when enabled for user
        'store_owner_tax_percent' => (int)(env('APP_TAX_PERCENT', 6) ?: 6),
    ],
    'store' => [
        // CONNECT_MODE__UNIQUE_REPLACE - store with the same provider_store_id will be replaced
        'connect_mode' => \App\Models\Store::CONNECT_MODE__UNIQUE_REPLACE
    ],
    'order' => [
        'auto_confirm' => [
             // true - order will be processed only when we can guess shipping method from shopify meta
             // false - when there is no shipping method from shopify, select the first shipping method
            'require_shipping_method' => false
        ]
    ],
    'public' => [
        'product' => [
            'wizard' => [
                'DEFAULT_DPI' => 300,
                'SCALE_RATIO' => 23,
                'PRINT_AREA_WIDTH' => 180,
                'DPI_LOW_LIMIT' => 75,
                'DPI_HIGH_LIMIT' => 600,
                'QUALITY_VALIDATION_LOW_LIMIT' => 80,

                // if false then 'Reg Tees', 'Wild tees' etc
                // will not be shown
                // TODO: changed to false by client's request
                'CATEGORIES_STEP_IS_ENABLED' => false,

                // for All Over prints only
                // when true - we will replace canvas preview with uploaded file
                // when false - we will combine uploaded file with site's preview
                // TODO: changed to true by client's request
                'ALL_OVER_PRINTS_PREVIEW_REPLACE' => true
            ]
        ]
    ]
];
