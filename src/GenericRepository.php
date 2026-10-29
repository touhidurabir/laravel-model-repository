<?php

namespace Touhidurabir\ModelRepository;

use Illuminate\Database\Eloquent\Model;

class GenericRepository extends BaseRepository
{
    /**
     * Constructor to bind model to repo
     *
     * @param  object<\Illuminate\Database\Eloquent\Model> $model
     * @return void
     */
    public function __construct(Model $model)
    {

        $this->model = $model;

        $this->modelClass = get_class($model);
    }
}
