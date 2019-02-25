<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@show');
Route::get('/cache:clear', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";
    
});
//Route::get('/home', 'HomeController@show');
Auth::routes();
Route::get('register/verify/{code}', 'Auth\VerifyController@verify');
Route::group(['middleware' => 'members'], function () {

    Route::group(['prefix' => 'dashboard','namespace'=>'Dashboard','as' => 'dashboard.'], function () {
        Route::get('/', function() {
            
            return redirect('/dashboard/store');
        
        });
        /*Store routes*/

        Route::get('/store', 'StoreController@index');

        Route::get('/store/{store_id}/sync', 'StoreController@syncView');

        Route::get('/store/{store_id}/update', 'StoreController@updateView');

        Route::post('/store/{store_id}/update', 'StoreController@update');
        
        Route::get('/store.json', 'StoreController@index');

        //faq
        Route::get('/faq', 'FaqController@index');
        
        //product-catalogs
        Route::get('/catalogs', 'CatalogsController@index');

        // print files gallery
        Route::get('/library', function() {
            return redirect('/dashboard/library/prints');
        });

        Route::get('/library/prints', 'PrintLibraryController@index');
        Route::get('/library/prints/search', 'PrintLibraryController@search');
        Route::post('/library/prints/upload', 'PrintLibraryController@uploadFile');

        Route::get('/library/{file_id}/download', 'PrintLibraryController@downloadFile');
        Route::post('/library/{file_id}/delete', 'PrintLibraryController@deleteFile');

        Route::get('/library/sources', 'SourceLibraryController@index');
        Route::get('/library/sources/search', 'SourceLibraryController@search');
        Route::post('/library/sources/upload', 'SourceLibraryController@uploadFile');
        });
        
});  

Route::group(['namespace' => 'Dashboard', 'prefix' => 'dashboard'], function () {
    // connect store
    // shopify Application URL
    Route::get('/store/connect/{provider}/initiate', 'StoreConnectController@initiate');

    // shopify Redirection URL
    Route::get('/store/connect/{provider}/confirm', 'StoreConnectController@confirm');

    // redirected to this after confirm
    Route::get('/store/connect/connect-to-account', 'StoreConnectController@connectToAccount');

    // account to connect shop is selected, will connect
    Route::get('/store/connect/connect-to-account/{account_type}', 'StoreConnectController@connectToAccount');

    Route::post('/store/{store}/api-settings/enable', 'StoreController@enableApi');
    
    Route::post('/store/{store}/api-settings/disable', 'StoreController@disableApi');

    Route::get('/store/{store}/api-settings/regenerate-token', 'StoreController@regenerateToken');

    Route::post('/store/{store}/save-settings/orders', 'StoreController@saveSettingsOrders');
        Route::get('/store/{store_id}/products', 'ProductsController@searchProductsForStore');
    // products
    Route::get('/products/categories', 'ProductsController@getCategories');
    Route::get('/products/template/{template_id}', 'ProductsController@getProductModelTemplate');
    Route::get('/products/{product_id}/get', 'ProductsController@getProduct');
    Route::get('/products/{product_id}/edit', 'ProductsController@edit');
    Route::post('/products/{product_id}/ignore', 'ProductsController@ignore');
    Route::post('/products/{product_id}/unignore', 'ProductsController@unignore');
    Route::post('/products/{product_id}/push', 'ProductsController@pushToStore');
    Route::post('/products/{product_id}/send-on-moderation', 'ProductsController@sendToModeration');
    Route::post('/products/{product_id}/delete', 'ProductsController@delete');
    Route::post('/products/send-to-moderation', 'ProductsController@createAndSendToModeration');
    // product variants
    Route::get('/product-variants/{product_variant_id}', 'ProductVariantsController@getVariant');
    Route::post('/product-variants/{product_variant_id}/update', 'ProductVariantsController@update');
    Route::post('/product-variants/{product_variant_id}/ignore', 'ProductVariantsController@ignore');
    Route::post('/product-variants/{product_variant_id}/unignore', 'ProductVariantsController@unignore');
    Route::post('/product-variants/{product_variant_id}/unsync', 'ProductVariantsController@unsync');

    // orders
    Route::post('/orders/webhook', 'OrdersController@webhook');
         Route::get('/orders/webhook', 'OrdersController@webhook');
    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/create', 'OrdersController@create');
    Route::get('/orders/{order_id}/update', 'OrdersController@updateView');
    //Route::post('/orders/{order_id}/update', 'OrdersController@update');
    Route::get('/orders/{order_id}/shipping', 'OrdersController@shippingView');
    Route::post('/orders/{order_id}/shipping', 'OrdersController@saveShipping');
    Route::get('/orders/{order_id}/review', 'OrdersController@reviewView');
    Route::post('/orders/{order_id}/review', 'OrdersController@saveReview');
    Route::post('/orders/{order_id}/cancel', 'OrdersController@cancel');
    Route::post('/orders/{order_id}/refund', 'OrdersController@refund');
    //Route::post('/orders/{order_id}/add-variant', 'OrdersController@addVariant');
    Route::post('/orders/{order_id}/update-variant/{variant_id}', 'OrdersController@updateVariant');
    //Route::post('/orders/{order_id}/copy-variant/{variant_id}', 'OrdersController@copyVariant');
    Route::post('/orders/{order_id}/attach-variants', 'OrdersController@attachVariants');
    Route::post('/orders/{order_id}/detach-variant/{variant_id}', 'OrdersController@detachVariant');
    Route::get('/orders/{order_id}/view-shopify', 'OrdersController@viewShopify');
    Route::get('/orders/{order_id}/get-with-new-shipping-price', 'OrdersController@getWithNewShippingPrice');

    
});

/*** Admin ***/
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['admin']], function () {
   
    Route::match(['get'], '/', ["as" => "admin", "uses" => "DashboardController@index"]);
   
    Route::match(['get'], 'users', ["as" => "users", "uses" => "UsersController@all"]);

    Route::match(['get'], 'users/add', ["as" => "users_add_get", "uses" => "UsersController@add"]);

    Route::match(['post'], 'users/add', ["as" => "users_add_post", "uses" => "UsersController@add"]);

    Route::match(['get'], 'users/{id}/edit', ["as" => "get_user_edit", "uses" => "UsersController@edit"]);

    Route::match(['post'], 'users/{id}/edit', ["as" => "get_user_edit", "uses" => "UsersController@edit"]);

    Route::match(['get'], 'users/{id}/delete', ["as" => "get_user_edit", "uses" => "UsersController@delete"]);
    
    Route::match(['get'], 'users/{id}/approve', ["as" => "approve_user", "uses" => "UsersController@approve"]);
    
    Route::match(['get'], 'users/{id}/reject', ["as" => "reject_user", "uses" => "UsersController@reject"]);
    
    Route::match(['get'], 'users/{id}/activate', ["as" => "activate_user", "uses" => "UsersController@activate"]);
     
    Route::match(['get'], 'users/{id}/ban', ["as" => "ban_user", "uses" => "UsersController@ban"]);
     
    Route::match(['get'], 'users/{id}/confirm', ["as" => "confirm_user", "uses" => "UsersController@confirm"]);

     // stores
     Route::get('stores', 'StoresController@all');
     Route::get('stores/{id}/show', 'StoresController@edit');
     Route::post('stores/{id}/show', 'StoresController@edit');
     Route::get('stores/{id}/recreate-webhooks', 'StoresController@recreateWebhooks');

 // products
     Route::get('products', 'ProductsController@all');
     Route::get('products/{id}/show', 'ProductsController@edit');
     Route::post('products/{id}/show', 'ProductsController@edit');
     Route::post('products/{id}/approve', 'ProductsController@approve');
     Route::post('products/{id}/decline', 'ProductsController@decline');
     Route::post('products/{id}/save-meta', 'ProductsController@saveMeta');


    /*Category Routes*/
    Route::match(['get'], 'catalog-categories', ["as" => "get_categories", "uses" => "CatalogCategoriesController@all"]);
   
    Route::match(['post'], 'catalog-categories', ["as" => "post_categories", "uses" => "CatalogCategoriesController@saveOrder"]);
    
    Route::match(['get'], 'catalog-categories/add', ["as" => "get_add_categories", "uses" => "CatalogCategoriesController@add"]);
    
    Route::match(['post'], 'catalog-categories/add', ["as" => "post_add_categories", "uses" => "CatalogCategoriesController@add"]);
    
    Route::match(['get'], 'catalog-categories/edit', ["as" => "get_edit_category", "uses" => "CatalogCategoriesController@edit"]);
    
    Route::match(['post'], 'catalog-categories/edit', ["as" => "post_edit_category", "uses" => "CatalogCategoriesController@edit"]);
    
    Route::match(['get','post'], 'catalog-categories/delete', ["as" => "get_delete_category", "uses" => "CatalogCategoriesController@delete"]);

    /*Catalog Attributes*/
    
    Route::match(['get'], 'catalog-attributes', ["as" => "get_categories", "uses" => "CatalogAttributesController@all"]);
    
    Route::match(['get'], 'catalog-attributes/add', ["as" => "get_add_categories", "uses" => "CatalogAttributesController@add"]);
    
    Route::match(['post'], 'catalog-attributes/add', ["as" => "post_add_categories", "uses" => "CatalogAttributesController@add"]);
    
    Route::match(['get'], 'catalog-attributes/{id}/edit', ["as" => "get_edit_category", "uses" => "CatalogAttributesController@edit"]);
    
    Route::match(['post'], 'catalog-attributes/{id}/edit', ["as" => "post_edit_category", "uses" => "CatalogAttributesController@edit"]);
    
    Route::match(['get','post'], 'catalog-attributes/{id}/delete', ["as" => "get_delete_category", "uses" => "CatalogAttributesController@delete"]);

    // catalog attribute options

    Route::match(['get'], 'catalog-attributes/{attribute_id}/options', ["as" => "get_categories", "uses" => "CatalogAttributeOptionsController@getByAttribute"]);
    
    Route::match(['get'], 'catalog-attributes/{attribute_id}/options/add', ["as" => "get_add_categories", "uses" => "CatalogAttributeOptionsController@add"]);
    
    Route::match(['post'], 'catalog-attributes/{attribute_id}/options/add', ["as" => "post_add_categories", "uses" => "CatalogAttributeOptionsController@add"]);
    
    Route::match(['get'], 'catalog-attributes/{attribute_id}/options/{id}/edit', ["as" => "get_edit_category", "uses" => "CatalogAttributeOptionsController@edit"]);
    
    Route::match(['post'], 'catalog-attributes/{attribute_id}/options/{id}/edit', ["as" => "post_edit_category", "uses" => "CatalogAttributeOptionsController@edit"]);

    Route::match(['get'], 'catalog-attributes/{attribute_id}/options/{id}/delete', ["as" => "delete_category", "uses" => "CatalogAttributeOptionsController@delete"]);
    
    // garment group
    Route::match(['get'], 'garment-groups', ["as" => "garment_groups", "uses" => "GarmentGroupsController@all"]);
    Route::match(['get'], 'garment-groups/{id}/edit', ["as" => "get_garment_groups", "uses" => "GarmentGroupsController@edit"]);
    Route::match(['post'], 'garment-groups/{id}/edit', ["as" => "post_garment_groups", "uses" => "GarmentGroupsController@edit"]);
    
    Route::match(['get'], 'garments', ["as" => "garment_groups", "uses" => "GarmentsController@all"]);
    Route::match(['get'], 'garments/{id}/edit', ["as" => "get_garment_edit", "uses" => "GarmentsController@edit"]);
    Route::match(['post'], 'garments/{id}/edit', ["as" => "post_garment_edit", "uses" => "GarmentsController@edit"]);
    
    // product models
    Route::get('product-models', 'ProductModelTemplatesController@all');
    Route::get('product-models/add', 'ProductModelTemplatesController@add');
    Route::post('product-models/add', 'ProductModelTemplatesController@add');
    Route::get('product-models/{id}/edit', 'ProductModelTemplatesController@edit');
    Route::post('product-models/{id}/edit', 'ProductModelTemplatesController@edit');
    Route::get('product-models/{id}/delete', 'ProductModelTemplatesController@delete');
    Route::post('product-models/{id}/pull-variants', 'ProductModelTemplatesController@pullVariants');
    Route::get('product-models/available', 'ProductModelTemplatesController@available');
    Route::get('product-models/complete', 'ProductModelTemplatesController@complete');
    Route::get('product-models/incomplete/source-templates', 'ProductModelTemplatesController@incompleteSourceTemplates');
    Route::get('product-models/incomplete/overlays', 'ProductModelTemplatesController@incompleteOverlays');
    Route::get('product-models/incomplete/prices', 'ProductModelTemplatesController@incompletePrices');
    Route::get('product-models/digital-catalog', 'ProductModelTemplatesController@allAsDigitalCatalog');
    //Add Product variants
    Route::get('product-variants/{id}/add','ProductModelVariations@add');

    // product designer files
    Route::get('product-designer-files/{id}/delete', 'ProductDesignerFilesController@delete');

    // files
    Route::get('files/{id}/delete', 'FilesController@delete');
    Route::post('files/{id}/delete', 'FilesController@delete');

    //product-model-options
    Route::match(['get'], 'variant-options', ["as" => "variants-options", "uses" => "VariantAttributesController@all"]);
    Route::match(['get'], 'variant-options/{id}/edit', ["as" => "variants-options", "uses" => "VariantAttributesController@edit"]);
    Route::match(['post'], 'variant-options/{id}/edit', ["as" => "variants-options", "uses" => "VariantAttributesController@edit"]);
   
    // global prices
    Route::get('add-price/{id}/add', 'GlobalPriceController@add');
    Route::post('add-price/{id}/add', 'GlobalPriceController@add');

    // orders
    Route::get('orders', 'OrdersController@all');
    Route::get('orders/refunds', 'OrdersController@refunds');
    Route::get('orders/without-shipping-groups', 'OrdersController@withoutShippingGroups');
    Route::get('orders/not-sent-to-kz-api', 'OrdersController@notSentToKZAPI');
    Route::get('orders/add', 'OrdersController@add');
    Route::post('orders/add', 'OrdersController@add');
    Route::get('orders/{id}/edit', 'OrdersController@edit');
    Route::post('orders/{id}/edit', 'OrdersController@edit');
    Route::get('orders/{id}/delete', 'OrdersController@delete');
    Route::get('orders/{id}/cancel', 'OrdersController@cancel');
    Route::get('orders/{id}/restore', 'OrdersController@restore');
    Route::get('orders/search-shopify', 'OrdersController@searchShopify');
    Route::post('orders/pull-from-shopify', 'OrdersController@pullFromShopify');
    // shipping settings
    Route::get('orders/shipping', 'ShippingGroupsController@all');
    Route::get('orders/shipping/add', 'ShippingGroupsController@add');
    Route::post('orders/shipping/add', 'ShippingGroupsController@add');
    Route::get('orders/shipping/{id}/edit', 'ShippingGroupsController@edit');
    Route::post('orders/shipping/{id}/edit', 'ShippingGroupsController@edit');
    Route::get('orders/shipping/{id}/delete', 'ShippingGroupsController@delete');
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

});
//Route::group(['middleware' => 'web'], function () {
  //  Route::get('/settings', 'Settings\DashboardController@show')->name('settings');
//});