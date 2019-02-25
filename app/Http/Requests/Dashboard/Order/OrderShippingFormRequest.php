<?php

namespace App\Http\Requests\Dashboard\Order;

use Geographer;

use App\Http\Requests\Request;

class OrderShippingFormRequest extends Request
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
        $codes = collect(Geographer::getCountries()->toArray())
            ->pluck('code')
            ->toArray();

        return [
            'shipping_method' => 'string|required',
            'full_name' => 'string|max:255',
            'first_name' => 'string|required|max:255',
            'last_name' => 'string|required|max:255',
            'address1' => 'string|required|max:255',
            'address2' => 'string|max:255',
            'city' => 'string|required|max:255',
            'province' => 'string|required|max:255',
            'country_code' => 'string|required|in:'.implode(',', $codes),
            'zip' => 'string|required|max:25',
            'company' => 'string|max:255',
            'phone' => 'string|max:255',
        ];
    }
}
