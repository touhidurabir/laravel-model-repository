<?php

namespace Touhidurabir\ModelRepository\Tests\Traits;

use Touhidurabir\ModelRepository\Facades\ModelRepository;
use Touhidurabir\ModelRepository\ModelRepositoryServiceProvider;

trait LaravelTestBootstrapping {

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app) {

        return [
            ModelRepositoryServiceProvider::class,
        ];
    }   
    
    
    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app) {
        
        return [
            'ModelUuid' => ModelRepository::class,
        ];
    }
}