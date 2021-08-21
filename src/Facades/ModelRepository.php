<?php

namespace Touhidurabir\ModelRepository\Facades;

use Illuminate\Support\Facades\Facade;

class ModelRepository extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {

        return 'model-repository';
    }
}