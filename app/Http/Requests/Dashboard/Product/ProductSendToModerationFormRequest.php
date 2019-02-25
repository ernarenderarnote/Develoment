<?php

namespace App\Http\Requests\Dashboard\Product;

use Gate;

use App\Http\Requests\Request;
use App\Models\ProductModel;
use App\Models\ProductModelTemplate;
use App\Models\Store;

class ProductSendToModerationFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
	{
		return [
			'existing_file_id.required' => trans('validation.required_front_print_file'),
			'existing_file_back_id.required' => trans('validation.required_back_print_file'),

		];
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isApi = (bool)(auth()->user()->token());

        $rules = [
            'store_id' => $isApi
                ? 'integer'
                : 'integer|required',
            'product_model_template_id' => 'integer|required|exists:product_model_templates,id',
            'product_title' => 'string|required|max:255',
            'product_description' => 'string|required',
            'model_id.*' => 'integer|required|exists:product_models,id',
            'retail_price.*' => 'numeric|required',
            'existing_file_id' => 'integer|nullable|exists:files,id',
            'existing_source_file_id' => 'integer|nullable|exists:files,id',
            'existing_file_back_id' => 'integer|nullable|exists:files,id',
            'existing_source_file_back_id' => 'integer|nullable|exists:files,id',

            'print_coordinates' => 'required',
        ];

        if ($isApi) {
            $rules = array_merge($rules, [
                'print_coordinates' => 'required',
                    'print_coordinates.left' => 'numeric|required_with:print_coordinates',
                    'print_coordinates.top' => 'numeric|required_with:print_coordinates',
                    'print_coordinates.width' => 'numeric|min:1|required_with:print_coordinates',
                    'print_coordinates.height' => 'numeric|min:1|required_with:print_coordinates'
            ]);
        }

        if ($isApi && $this->get('existing_file_id')) {
            $rules = array_merge($rules, [
                'canvas_size.width' => 'numeric|min:1|required_with:existing_file_id',
                'canvas_size.height' => 'numeric|min:1|required_with:existing_file_id',
            ]);
        }

        if ($isApi && $this->get('existing_file_back_id')) {
            $rules = array_merge($rules, [
                'canvas_size_back.width' => 'numeric|min:1|required_with:existing_file_back_id',
                'canvas_size_back.height' => 'numeric|min:1|required_with:existing_file_back_id'
            ]);
        }

        $store_id = $this->get('store_id');
        if ($store_id) {
            $store = Store::find($store_id);
            if ($store) {
                if (Gate::denies('edit', $store)) {
                    return abort(403, trans('messages.not_authorized_to_access_store'));
                }
            }
        }

        $product_model_template_id = $this->get('product_model_template_id');
        if ($product_model_template_id) {
            $template = ProductModelTemplate::find($product_model_template_id);
            if (!$template || !$template->isVisible()) {
                return abort(403, trans('messages.selected_model_template_is_not_available'));
            }

            if ($template->backPrintCanBeAddedOnItsOwn()) {
                $rules['existing_file_id'] = 'integer|nullable|required_without:existing_file_back_id|exists:files,id';
                $rules['existing_file_back_id'] = 'integer|nullable|required_without:existing_file_id|exists:files,id';
            }
            else {
                $rules['existing_file_id'] = 'integer|required|exists:files,id';
            }

            // for all over prints we only upload an image
            if ($template->garment->isAllOverPrintOrSimilar()) {
                $rules = array_merge($rules, [
                    'print_coordinates' => '',
                    'print_coordinates.left' => '',
                    'print_coordinates.top' => '',
                    'print_coordinates.width' => '',
                    'print_coordinates.height' => '',
                    'canvas_size.width' => '',
                    'canvas_size.height' => ''
                ]);
            };
        }

        $model_ids = $this->get('model_id');
        $models = ProductModel::whereIn('id', $model_ids)->get();
        foreach($models as $model) {
            if ($model->isOutOfStock()) {
                return abort(403, trans('messages.selected_option_not_available', [
                    'id' => $model->id
                ]));
            }
        }

        return $rules;
    }
}
