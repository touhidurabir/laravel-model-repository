<?php

namespace Touhidurabir\ModelRepository;

use Illuminate\Support\ServiceProvider;
use Touhidurabir\ModelRepository\Console\Repository;

class ModelRepositoryServiceProvider extends ServiceProvider {
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        if ( $this->app->runningInConsole() ) {
			$this->commands([
				Repository::class
			]);
		}

        $this->mergeConfigFrom(
            __DIR__.'/../config/model-repository.php', 'model-repository'
        );
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        $this->publishes([
            __DIR__.'/../config/model-repository.php' => base_path('config/model-repository.php'),
        ], 'config');
    }
    
}