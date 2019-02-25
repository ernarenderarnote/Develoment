<?php

namespace App\Providers;

use Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function register() {}

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiError', function($message = '', $data = [], $code = 200) {
            
            if (!is_array($data)) {
                $code = $data;
                $data = [];
            }
            
            return response()->json([
                'status' => $code,
                'isError' => true,
                'message' => $message,
                'data' => $data
            ], $code);
        });
        
        Response::macro('api', function($message = '', $data = [], $code = 200) {
            
            // when the second parameter is status code
            if (!is_array($data) && is_int($data)) {
                $code = $data;
                $data = [];
            }
            
            // when the first parameter is data
            if (is_array($message) && empty($data)) {
                $data = $message;
                $message = null;
            }
            
            return response()->json([
                'status' => $code,
                'isError' => false,
                'message' => $message,
                'data' => $data
            ], $code);
        });
        
        Response::macro('apiValidationErrors', function($validator) {
            return response()->json([
                'status' => 422,
                'isError' => true,
                'message' => trans('Validation error'),
                'validationErrors' => (method_exists($validator, 'messages') ? $validator->messages() : $validator),
                'data' => []
            ], 422);
        });
    }

}
