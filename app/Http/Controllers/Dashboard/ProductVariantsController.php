<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Auth;
use App;
use Gate;
use DB;
use Exception;
use Log;
use Storage;

use App\Components\Shopify;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Store\CreateStoreFormRequest;
use App\Http\Requests\Dashboard\ProductVariant\ProductVariantAttachFormRequest;

use App\Models\ProductVariant;
use App\Models\ProductModel;
use App\Models\File;

class ProductVariantsController extends Controller
{
    /**
     * Get single variant
     */
    public function getVariant(Request $request, $product_variant_id)
    {
        $variant = ProductVariant::find($product_variant_id);
        if (Gate::denies('show', $variant)) {
            return abort(403, trans('messages.not_authorized_to_access_product_variant'));
        }

        return response()->api(null, [
            'productVariant' => $variant->transformFull()
        ]);
    }

    /**
     * Change product variant status
     */
        public function ignore(Request $request, $product_variant_id)
        {
            $variant = ProductVariant::find($product_variant_id);
            if (Gate::denies('edit', $variant)) {
                return abort(403, trans('messages.not_authorized_to_access_product_variant'));
            }

            $variant->ignore();

            return $this->returnSuccess(trans('messages.product_variant_updated'));
        }

        public function unignore(Request $request, $product_variant_id)
        {
            $variant = ProductVariant::find($product_variant_id);
            if (Gate::denies('edit', $variant)) {
                return abort(403, trans('messages.not_authorized_to_access_product_variant'));
            }

            $variant->activate();

            return $this->returnSuccess(trans('messages.product_variant_updated'));
        }

    /**
     *
     */
    public function update(ProductVariantAttachFormRequest $request)
    {
        if (!env('TURN_ON_FEATURE__PRODUCT_VARIANTS_EDITING')) {
            return abort(404);
        }

        $product_variant_id = $request->get('product_variant_id');
        $product_model_id = $request->get('model_id');
        $existing_file_id = $request->get('existing_file_id');
        $preview_canvas_data = $request->get('preview_canvas_data');

        $variant = ProductVariant::find($product_variant_id);
        if (Gate::denies('edit', $variant)) {
            return abort(403, trans('messages.not_authorized_to_access_product_variant'));
        }

        $model = ProductModel::find($product_model_id);

        if (!$model) {
            return abort(403, trans('messages.selected_model_is_not_available'));
        }

        $file = null;
        if ($existing_file_id) {
            $file = File::find($existing_file_id);

            if (!$file || !auth()->user()->isOwnerOf($file)) {
                return abort(403, trans('messages.selected_file_is_not_available'));
            }
        }

        DB::beginTransaction();

        try {
            $variant->product_model_id = $model->id;
            $variant->save();

            $variant->files()->detach();
            if ($file) {
                $variant->files()->save($file, [
                    'type' => File::TYPE_PRINT_FILE
                ]);

                $mockup = File::saveMockupFromCanvas($preview_canvas_data);
                if ($mockup) {
                    $variant->mockups()->save($mockup, [
                        'type' => File::TYPE_PRINT_FILE_MOCKUP
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            \Bugsnag::notifyException($e);
            return abort(500, trans('messages.product_variant_cannot_be_updated'));
        }

        DB::commit();

        return response()->api(trans('messages.product_variant_updated'), [
            'productVariant' => $variant->transformFull()
        ]);
    }


    /**
     * Unsync (delete relation) product variant
     */
        public function unsync(Request $request, $product_variant_id)
        {
            if (!env('TURN_ON_FEATURE__PRODUCT_VARIANTS_EDITING')) {
                return abort(404);
            }

            $variant = ProductVariant::find($product_variant_id);
            if (Gate::denies('edit', $variant)) {
                return abort(403, trans('messages.not_authorized_to_access_product_variant'));
            }

            $variant->unsync();

            return $this->returnSuccess(trans('messages.product_variant_unsynced'));
        }

    /**
     * moderation
     */
        public function sendToModeration(Request $request, $product_variant_id)
        {
            if (!env('TURN_ON_FEATURE__PRODUCT_VARIANTS_MODERATION')) {
                return abort(404);
            }

            $variant = ProductVariant::find($product_variant_id);
            if (Gate::denies('edit', $variant) && $variant->product->store->isInSync()) {
                return abort(403, trans('messages.not_authorized_to_access_product_variant'));
            }

            $variant->changeModerationStatusTo(
                ProductVariant::MODERATION_STATUS_ON_MODERATION,
                ''
            );

            return $this->returnSuccess(trans('messages.product_variant_sent_on_moderation'));
        }

}
