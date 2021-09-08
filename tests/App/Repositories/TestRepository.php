<?php

namespace App\Repositories;

use Touhidurabir\ModelRepository\BaseRepository;
use App\Models\Test;

class TestRepository extends BaseRepository {

	/**
     * Constructor to bind model to repo
     *
     * @param  object<App\Models\Test> $test
     * @return void
     */
    public function __construct(Test $test) {

        $this->model = $test;

        $this->modelClass = get_class($test);
    }

}
