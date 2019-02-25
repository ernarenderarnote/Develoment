<?php

namespace App\Http\Controllers\Api\StoreApi;

use FractalManager;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Dingo\Api\Exception\ValidationHttpException;

class StoreApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use \Dingo\Api\Routing\Helpers;
    
    protected $perPage = 10;
    
    public function __construct()
    {
        
    }
    
    protected function getStore()
    {
        return auth()->user()->token()->store;
    }
    
    protected function item($model, $transformer)
    {
        return FractalManager::serializeItem($model, $transformer);
    }
    
    protected function paginator($model, $transformer)
    {
        return FractalManager::serializePaginator($model, $transformer);
    }
    
    /**
     * @Override
     */
    protected function throwValidationException($request, $validator) {
        throw new ValidationHttpException($validator->errors());
    }
}


