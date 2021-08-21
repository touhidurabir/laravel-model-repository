<?php

namespace Touhidurabir\ModelRepository\Tests\App\Repositories;

use Touhidurabir\ModelRepository\BaseRepository;
use Touhidurabir\ModelRepository\Tests\App\Models\Profile;

class ProfileRepository extends BaseRepository {

	/**
     * Constructor to bind model to repo
     *
     * @param  object<Touhidurabir\ModelRepository\Tests\App\Models\Profile> $profile
     * @return void
     */
    public function __construct(Profile $profile) {

        $this->model = $profile;

        $this->modelClass = get_class($profile);
    }

}
