<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::group([
    'middleware' => 'auth:api'
], function () {
    //
});
$api = app('Dingo\Api\Routing\Router');

$api->version(['v1'], [
    'middleware' => ['api', 'auth:api', 'api.store'],
    'prefix' => 'api/store-api/v1',
    'namespace' => 'App\Http\Controllers\Api\StoreApi'
], function($api) {

    // store
        $api->get('store/settings', 'StoreController@getSettings');
        $api->put('store/settings/orders', 'StoreController@updateOrdersSettings');
        $api->post('store/settings/orders/reset-charges', 'StoreController@resetCharges');

    // products
        Route::bind('product', function ($value) {
            return App\Models\Product::find($value);
            //return App\Models\Product::findByHashId($value);
        });
        $api->get('products/categories', 'ProductsController@getCategories');
        $api->get('products/template/{template_id}', 'ProductsController@getProductModelTemplate');
        $api->post('products/{product_id}/push-to-store', 'ProductsController@pushToStore');
        $api->post('products/{product}/ignore', 'ProductsController@ignore');
        $api->post('products/{product}/unignore', 'ProductsController@unignore');
        $api->resource('products', 'ProductsController', ['except' => [
            'create', 'edit'
        ]]);

        Route::bind('variant', function ($value) {
            return App\Models\ProductVariant::find($value);
            //return App\Models\ProductVariant::findByHashId($value);
        });
        $api->post('products/{product}/variants/{variant}/ignore', 'ProductVariantsController@ignore');
        $api->post('products/{product}/variants/{variant}/unignore', 'ProductVariantsController@unignore');

    // print files gallery
        $api->get('library/prints/search', 'PrintLibraryController@search');
        $api->post('library/prints/upload', 'PrintLibraryController@uploadFile');

        $api->get('library/{file_id}', 'PrintLibraryController@getFile');
        $api->get('library/{file_id}/download', 'PrintLibraryController@downloadFile');
        $api->post('library/{file_id}/delete', 'PrintLibraryController@deleteFile');

        $api->get('library/sources/search', 'PrintLibraryController@searchSource');
        $api->post('library/sources/upload', 'PrintLibraryController@uploadSourceFile');

    // orders
        Route::bind('order', function ($value) {
            return App\Models\Order::find($value);
            //return App\Models\Order::findByHashId($value);
        });
        $api->put('orders/{order}/shipping', 'OrdersController@saveShipping');
        $api->post('orders/{order}/pay', 'OrdersController@pay');
        $api->post('orders/{order}/cancel', 'OrdersController@cancel');
        $api->post('orders/{order}/refund', 'OrdersController@refund');
        $api->resource('orders', 'OrdersController', ['only' => [
            'index', 'store', 'show'
        ]]);

        $api->put('orders/{order}/update-variant/{variant}', 'OrdersController@updateVariant');
        $api->post('orders/{order}/attach-variants', 'OrdersController@attachVariants');
        $api->delete('orders/{order}/detach-variant/{variant}', 'OrdersController@detachVariant');
});


/**
 * Inner API
 */
Route::group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => 'api'], function () {

    Route::get('orders/fulfill', 'KZApiController@fulfillOrder');

    Route::post('orders/retry-kz-orders-notify', 'KZApiController@retryKZOrdersNotify');

    Route::post('products/inventory-notification', 'KZApiController@inventoryNotification');
});
/**
 * Webhooks - Allowed for anonymous access
 */
Route::group(['middleware' => ['webhooks']], function () {
    // app webhooks
        Route::post('/dashboard/store/webhook', 'Dashboard\StoreController@webhook');

    // product webhooks
        Route::post('/dashboard/products/webhook', 'Dashboard\ProductsController@webhook');

    // order webhooks
        Route::post('/dashboard/orders/webhook', 'Dashboard\OrdersController@webhook');
         Route::get('/dashboard/orders/webhook', 'Dashboard\OrdersController@webhook');
    // braintree webhooks
        Route::post('/braintree-webhooks', 'BraintreeWebhookController@webhook');
});