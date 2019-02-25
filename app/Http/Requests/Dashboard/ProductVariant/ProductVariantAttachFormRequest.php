<?php

namespace App\Http\Requests\Dashboard\ProductVariant;

use App\Http\Requests\Request;

use App\Models\ProductModel;

class ProductVariantAttachFormRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_variant_id' => 'integer|required',
            'model_id' => 'integer|required|exists:product_models,id',
            'existing_file_id' => 'integer|required',
        ];
    }
}
