<?php

namespace Touhidurabir\ModelRepository;

use Illuminate\Support\ServiceProvider;
use Touhidurabir\ModelRepository\Console\Repository;

class ModelRepositoryServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        if ( $this->app->runningInConsole() ) {
			$this->commands([
				Repository::class
			]);
		}

        $this->publishes([
            __DIR__.'/../config/model-repository.php' => base_path('config/model-repository.php'),
        ], 'config');
    }

    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $this->mergeConfigFrom(
            __DIR__.'/../config/model-repository.php', 'model-repository'
        );
    }
    
}