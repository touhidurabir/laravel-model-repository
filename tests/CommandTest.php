<?php

namespace Touhidurabir\ModelRepository\Tests;

use RuntimeException;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Artisan;
use Touhidurabir\ModelRepository\Tests\Traits\LaravelTestBootstrapping;

/**
 *  TO-DO: Need more testing.
 *  File existence, directory existence, path accesseable etc
 */
class CommandTest extends TestCase {

    use LaravelTestBootstrapping;

    /**
     * @test
     */
    public function repository_command_will_run() {

        $command = $this->artisan('make:repository', ['class' => 'UserRepository']);

        $command->assertExitCode(0);

        $command = $this->artisan('make:repository', ['class' => 'UserRepository', '--model' => 'App\\Models\\User']);

        $command->assertExitCode(0);
    }


    /**
     * @test
     */
    public function repository_command_will_fail_if_repository_class_not_give() {

        $command = $this->artisan('make:repository');

        $this->expectException(RuntimeException::class);
    }


    /**
     * @test
     */
    public function repository_command_will_not_create_class_if_already_exists() {

        $this
            ->artisan('make:repository', ['class' => 'ProfileRepository'])
            // ->expectsOutput("class ProfileRepository already exist at path /App/Repositories")
            ->assertExitCode(0);
    }

}