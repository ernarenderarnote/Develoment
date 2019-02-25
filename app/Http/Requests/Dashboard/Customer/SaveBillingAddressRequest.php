<?php

namespace App\Http\Requests\Dashboard\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Request;

class SaveBillingAddressRequest extends Request
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
            'address' => 'required|max:255',
            'address_line_2' => 'max:255',
            'city' => 'required|max:255',
            'state' => 'required|max:255',
            'zip' => 'required|max:25',
            'country' => 'required|max:2',
        ];
    }
    
    public function response(array $errors)
    {
        if ($this->ajax() || $this->wantsJson()) {
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
