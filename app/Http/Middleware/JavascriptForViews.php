<?php

namespace App\Http\Middleware;

use JavaScript;
use Closure;
use Illuminate\Support\Facades\Auth;

use FractalManager;
use App\Models\Store;
use App\Transformers\Store\StoreBriefTransformer;

/*
 * Use for debugging purpose
 */
class JavascriptForViews
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $data = [
            'csrfToken' => csrf_token(),
            'data' => [],
            'models' => [],
            'urls' => [
                'root' => url('')
            ],
            'constants' => [
                'Models' => [
                    'CatalogAttribute' => [
                        'ATTRIBUTE_COLOR' => \App\Models\CatalogAttribute::ATTRIBUTE_COLOR
                    ],
                    'ProductClientFile' => [
                        'LOCATION_FRONT_CHEST' => \App\Models\ProductClientFile::LOCATION_FRONT_CHEST,
                        'LOCATION_BACK'        => \App\Models\ProductClientFile::LOCATION_BACK
                    ],
                    'ProductVariant' => [
                        'PRINT_SIDE_FRONT' => \App\Models\ProductVariant::PRINT_SIDE_FRONT,
                        'PRINT_SIDE_BACK'  => \App\Models\ProductVariant::PRINT_SIDE_BACK,
                        'PRINT_SIDE_ALL'   =>  \App\Models\ProductVariant::PRINT_SIDE_ALL
                    ],
                    'ProductModel' => [
                        'INVENTORY_STATUS_IN_STOCK' => \App\Models\ProductModel::INVENTORY_STATUS_IN_STOCK,
                        'INVENTORY_STATUS_OUT_OF_STOCK' => \App\Models\ProductModel::INVENTORY_STATUS_OUT_OF_STOCK
                    ],
                    'Garment' => [
                        'SLUG_T_SHIRT'        =>  \App\Models\Garment::SLUG_T_SHIRT,
                        'SLUG_TANK_TOP'       =>  \App\Models\Garment::SLUG_TANK_TOP,
                        'SLUG_OTHER'          =>  \App\Models\Garment::SLUG_OTHER,
                        'SLUG_ALL_OVER_PRINT' =>  \App\Models\Garment::SLUG_ALL_OVER_PRINT
                    ],
                    'Notification' => [
                        'TYPE_DEFAULT'      =>  \App\Models\Notification::TYPE_DEFAULT,
                        'TYPE_ANNOUNCEMENT' =>  \App\Models\Notification::TYPE_ANNOUNCEMENT
                    ],
                    'Order' => [
                        'SHIPPING_METHOD_FIRST_CLASS'   =>  \App\Models\Order::SHIPPING_METHOD_FIRST_CLASS,
                        'SHIPPING_METHOD_PRIORITY_MAIL' =>  \App\Models\Order::SHIPPING_METHOD_PRIORITY_MAIL
                    ]
                ]
            ]
        ];

        if (auth()->user() && auth()->user()->isAdmin()) {
            $data['data']['shopifyStores'] = FractalManager::serializeCollection(
                Store::findSynced(),
                new StoreBriefTransformer
            );
        }

        JavaScript::put($data);

        return $next($request);
    }
}
