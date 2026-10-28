<?php

namespace Touhidurabir\ModelRepository\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Touhidurabir\ModelRepository\GenericRepository make(\Illuminate\Database\Eloquent\Model|string $model)
 *
 * @see \Touhidurabir\ModelRepository\RepositoryFactory
 */
class ModelRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {

        return 'model-repository';
    }
}
