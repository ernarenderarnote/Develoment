<?php

namespace App\Http\Requests\Dashboard\Support;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\Request;

class OpenTicketFormRequest extends ApiRequest
{    
    public function authorize()
    {
        return true;
    }
	
    public function rules()
    {
        return [
            'subject' => 'string|required|max:255',
            'text' => 'string|required'
        ];
    }
}
