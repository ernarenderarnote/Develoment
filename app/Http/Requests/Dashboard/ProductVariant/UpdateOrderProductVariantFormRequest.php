<?php

namespace App\Http\Requests\Dashboard\ProductVariant;

use App\Http\Requests\Request;
use App\Models\ProductModel;

class UpdateOrderProductVariantFormRequest extends Request
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
            'retail_price' => 'numeric',
            'quantity' => 'integer',
            'name' => 'string',
            'product_model_id' => 'integer|exists:product_models,id',
            'existing_file_id' => 'integer',
        ];
    }
}
