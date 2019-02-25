<?php

namespace App\Providers;

use Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {            
        // override validation error responses for API
        app('Dingo\Api\Exception\Handler')
            ->register(function (\Illuminate\Validation\ValidationException $exception) {
                throw new \Dingo\Api\Exception\ValidationHttpException($exception->validator->errors());
            });
        
    }
}
