<?php

namespace Touhidurabir\ModelRepository;

use InvalidArgumentException;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;

class RepositoryFactory
{
    /**
     * Make a repository for the given model instance or model class
     *
     * @param  object<\Illuminate\Database\Eloquent\Model>|string $model
     * @return object<\Touhidurabir\ModelRepository\GenericRepository>
     *
     * @throws \InvalidArgumentException
     */
    public function make($model)
    {

        if (is_string($model) && is_subclass_of($model, Model::class)) {

            $model = Container::getInstance()->make($model);
        }

        if (! $model instanceof Model) {

            throw new InvalidArgumentException(
                'The model must be an instance or class name of ' . Model::class . '.'
            );
        }

        return new GenericRepository($model);
    }
}
