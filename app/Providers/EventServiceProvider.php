<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // user
            'App\Events\User\UserCreatedEvent' => [
                'App\Listeners\User\OnUserCreatedEvent'
            ],
            'App\Events\User\UserStatusChangedEvent' => [
                'App\Listeners\User\OnUserStatusChangedEvent'
            ],
            'App\Events\User\UserChargedEvent' => [
                'App\Listeners\User\OnUserChargedEvent'
            ],

        // store
            'App\Events\Store\StoreCreatedEvent' => [
                'App\Listeners\Store\OnStoreCreatedEvent'
            ],

        // product
            'App\Events\Product\ProductModerationStatusChangedEvent' => [
                'App\Listeners\Product\OnProductModerationStatusChangedEvent'
            ],

        // product variant
            'App\Events\ProductVariant\ProductVariantModerationStatusChangedEvent' => [
                'App\Listeners\ProductVariant\OnProductVariantModerationStatusChangedEvent'
            ],

        // product model template
            'App\Events\ProductModelTemplate\ProductModelTemplateSavedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

            'App\Events\ProductModelTemplate\ShippingGroupAssignedEvent' => [
                'App\Listeners\ProductModelTemplate\OnShippingGroupAssignedEvent'
            ],

        // product model
            'App\Events\ProductModel\ProductModelSavedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

            'App\Events\ProductModel\ProductModelInventoryStatusChangedEvent' => [
                'App\Listeners\ProductModel\OnProductModelInventoryStatusChangedEvent'
            ],

        // payment
            'App\Events\Payment\AutoPaymentFailedEvent' => [
                'App\Listeners\Payment\OnAutoPaymentFailedEvent'
            ],

        // payment
            'App\Events\Payment\AutoPaymentFailedEvent' => [
                'App\Listeners\Payment\OnAutoPaymentFailedEvent'
            ],

        // order
            'App\Events\Order\OrderActionRequiredEvent' => [
                'App\Listeners\Order\OnOrderActionRequiredEvent'
            ],

            'App\Events\Order\OrderPaidEvent' => [
                'App\Listeners\Order\OnOrderPaidEvent'
            ],

            'App\Events\Order\OrderRefundStatusChangedEvent' => [
                'App\Listeners\Order\OnOrderRefundStatusChangedEvent'
            ],

            'App\Events\Order\OrderResolvedActionRequiredEvent' => [
                'App\Listeners\Order\OnOrderResolvedActionRequiredEvent'
            ],

            'App\Events\Order\OrderVariantPriceMissedEvent' => [
                'App\Listeners\Order\OnOrderVariantPriceMissedEvent'
            ],

        // support
            'App\Events\Support\TicketOpenedEvent' => [
                'App\Listeners\Support\OnTicketOpenedEvent'
            ],

            'App\Events\Support\RefundOpenedEvent' => [
                'App\Listeners\Support\OnRefundOpenedEvent'
            ],

        // price modifiers
            'App\Events\PriceModifier\PriceModifierCreatedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

            'App\Events\PriceModifier\PriceModifierSavedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

            'App\Events\PriceModifier\PriceModifierDeletedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

        // categories
            'App\Events\CatalogCategory\CatalogCategoryCreatedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

            'App\Events\CatalogCategory\CatalogCategorySavedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],

            'App\Events\CatalogCategory\CatalogCategoryDeletedEvent' => [
                'App\Listeners\Cache\OnCategoriesClearCacheEvent',
                'App\Listeners\Cache\OnProductModelTemplatesClearCacheEvent',
            ],
        // User Related Events...
        'Laravel\Spark\Events\Auth\UserRegistered' => [
            'Laravel\Spark\Listeners\Subscription\CreateTrialEndingNotification',
        ],
        'Laravel\Spark\Events\Subscription\UserSubscribed' => [
            'Laravel\Spark\Listeners\Subscription\UpdateActiveSubscription',
            'Laravel\Spark\Listeners\Subscription\UpdateTrialEndingDate',
        ],

        'Laravel\Spark\Events\Profile\ContactInformationUpdated' => [
            'Laravel\Spark\Listeners\Profile\UpdateContactInformationOnBraintree',
        ],

        'Laravel\Spark\Events\PaymentMethod\VatIdUpdated' => [
            'Laravel\Spark\Listeners\Subscription\UpdateTaxPercentageOnBraintree',
        ],

        'Laravel\Spark\Events\PaymentMethod\BillingAddressUpdated' => [
            'Laravel\Spark\Listeners\Subscription\UpdateTaxPercentageOnBraintree',
        ],

        'Laravel\Spark\Events\Subscription\SubscriptionUpdated' => [
            'Laravel\Spark\Listeners\Subscription\UpdateActiveSubscription',
        ],

        'Laravel\Spark\Events\Subscription\SubscriptionCancelled' => [
            'Laravel\Spark\Listeners\Subscription\UpdateActiveSubscription',
        ],

        // Team Related Events...
        'Laravel\Spark\Events\Teams\TeamCreated' => [
            'Laravel\Spark\Listeners\Teams\UpdateOwnerSubscriptionQuantity',
        ],

        'Laravel\Spark\Events\Teams\TeamDeleted' => [
            'Laravel\Spark\Listeners\Teams\UpdateOwnerSubscriptionQuantity',
        ],

        'Laravel\Spark\Events\Teams\TeamMemberAdded' => [
            'Laravel\Spark\Listeners\Teams\UpdateTeamSubscriptionQuantity',
        ],

        'Laravel\Spark\Events\Teams\TeamMemberRemoved' => [
            'Laravel\Spark\Listeners\Teams\UpdateTeamSubscriptionQuantity',
        ],

        'Laravel\Spark\Events\Teams\Subscription\TeamSubscribed' => [
            'Laravel\Spark\Listeners\Teams\Subscription\UpdateActiveSubscription',
            'Laravel\Spark\Listeners\Teams\Subscription\UpdateTrialEndingDate',
        ],

        'Laravel\Spark\Events\Teams\Subscription\SubscriptionUpdated' => [
            'Laravel\Spark\Listeners\Teams\Subscription\UpdateActiveSubscription',
        ],

        'Laravel\Spark\Events\Teams\Subscription\SubscriptionCancelled' => [
            'Laravel\Spark\Listeners\Teams\Subscription\UpdateActiveSubscription',
        ],

        'Laravel\Spark\Events\Teams\UserInvitedToTeam' => [
            'Laravel\Spark\Listeners\Teams\CreateInvitationNotification',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
