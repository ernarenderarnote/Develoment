<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Store' => 'App\Policies\StorePolicy',
        'App\Models\Product' => 'App\Policies\ProductPolicy',
        'App\Models\ProductVariant' => 'App\Policies\ProductVariantPolicy',
        'App\Models\Order' => 'App\Policies\OrderPolicy',
        'App\Models\File' => 'App\Policies\FilePolicy',
        'App\Models\FileAttachment' => 'App\Policies\FilePolicy',
        'Laravel\Spark\Notification' => 'App\Policies\NotificationPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
