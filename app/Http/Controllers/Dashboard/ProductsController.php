<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Gate;
use DB;
use Cache;
use Log;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Transformers\Product\ProductWithVariantsTransformer;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductModelTemplate;
use App\Models\CatalogCategory;
use App\Models\CatalogAttribute;

use App\Transformers\CatalogCategory\CatalogCategoryTransformer;

class ProductsController extends Controller
{
    use \App\Http\Controllers\Dashboard\Traits\Products\WebhookTrait;
    use \App\Http\Controllers\Dashboard\Traits\Products\PushToStoreTrait;
    use \App\Http\Controllers\Dashboard\Traits\Products\SendToModerationTrait;

    /*
     * API endpoints
     */
        public function getCategories()
        {

            // get cached
                $ttl = config('settings.cache.ttl.category.get_on_frontend');
                $cacheValueName = static::class.':'.(__FUNCTION__);
                
                if (!Cache::has($cacheValueName)) {

                    // the work
                        if (
                            config('settings.public.product.wizard.CATEGORIES_STEP_IS_ENABLED')
                        ) {
                            $categories = $this->serializeCollection(
                                CatalogCategory::getDefaultRootCategoriesTree(),
                                new CatalogCategoryTransformer
                            );
                            
                        }
                        else {
                            $categories = [
                                array_merge(
                                    CatalogCategory::getDefaultRoot()->transformIncluded(),
                                    [
                                        'templates' => ProductModelTemplate::transformIncludedCollection(
                                            ProductModelTemplate::getAllVisible()
                                        )
                                    ]
                                )
                            ];

                        }

                        $data = [
                            'categories' => $categories,
                            'attributes' => CatalogAttribute::transformAll()
                        ];

                    Cache::put(
                        $cacheValueName,
                        $data,
                        Carbon::now()->addMinutes($ttl)
                    );
                }
                $data = Cache::get($cacheValueName);

            return response()->api(null, $data);
        }

        public function getProductModelTemplate(Request $request, $template_id)
        {

            $ttl = config('settings.cache.ttl.product_model_template.get_on_frontend');
            $cacheValueName = static::class.':'.(__FUNCTION__).':'.$template_id;

            if (!Cache::has($cacheValueName)) {
                $templateQuery = ProductModelTemplate::with('models')
                    ->with('image')
                    ->with('preview')
                    ->with('example')
                    ->with('overlay')
                    ->with('overlayBack')
                    ->with('garment');

                if (getenv('APP_ENV') != 'local') {
                    $templateQuery->complete();
                }

                $template = $templateQuery->find($template_id);

                if ($template) {
                    $template = $template->transformFull();
                }

                Cache::put(
                    $cacheValueName,
                    $template,
                    Carbon::now()->addMinutes($ttl)
                );
            }

            $template = Cache::get($cacheValueName);

            return response()->api(null, [
                'template' => $template,
            ]);
        }

        public function getProduct(Request $request, $product_id)
        {
            $product = Product::findOrFail($product_id);
            $this->authorize('edit', $product);

            return response()->api(null, [
                'product' => $product->transformForEditing()
            ]);
        }

        /**
         * Search store vendor synced products
         */
        public function searchProductsForStore(Request $request, $store_id)
        {
            $filters = $request->get('filters');

            $search = null;
            if (!empty($filters['search'])) {
                $search = filter_var($filters['search'], FILTER_SANITIZE_STRING);
            }

            $store = Store::findOrFail($store_id);
            $this->authorize('edit', $store);

            $query = $store->vendorProductsAllowedDirectOrder()
                ->where('name', 'LIKE', '%'.$search.'%');

            return response()->api([
                'products' => $this->serializePginator($query, new ProductWithVariantsTransformer, 8)
            ]);
        }


    /*
     * Update product view
     */
    public function edit(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $this->authorize('edit_variants', $product);

        return view('pages.dashboard.products.update', [
            'product' => $product
        ]);
    }

    /**
     * Change product status
     */
        public function ignore(Request $request, $product_id)
        {
            $product = Product::findOrFail($product_id);
            $this->authorize('ignore', $product);

            $product->ignore();

            return $this->returnSuccess(trans('messages.product_updated'));
        }

        public function unignore(Request $request, $product_id)
        {
            $product = Product::findOrFail($product_id);
            $this->authorize('unignore', $product);

            $product->activate();

            return $this->returnSuccess(trans('messages.product_updated'));
        }



    public function delete(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        $this->authorize('delete', $product);

        DB::beginTransaction();
        try {
            foreach ($product->variants as $variant) {
                $variant->delete();
            }
            $product->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            \Bugsnag::notifyException($e);
            return abort(500, trans('messages.product_variant_cannot_be_updated'));
        }
        DB::commit();

        return $this->returnSuccess(trans('messages.product_deleted'));
    }
}
