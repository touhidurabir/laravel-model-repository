<?php

namespace Touhidurabir\ModelRepository\Tests;

use Exception;
use RuntimeException;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Touhidurabir\ModelRepository\Tests\Traits\FileHelpers;
use Touhidurabir\ModelRepository\Tests\Traits\LaravelTestBootstrapping;

/**
 *  TO-DO: Need more testing.
 *  File existence, directory existence, path accesseable etc
 */
class CommandTest extends TestCase {

    use LaravelTestBootstrapping;

    use FileHelpers;

    /**
     * Repository class store full absolute path based on config settings
     *
     * @var string
     */
    protected $repositoryStoreFullPath;


    /**
     * Generate the repository class store full absolute path based on config settings
     *
     * @return void
     */
    protected function generateRepositoryClassStoreFullPath() {

        $this->repositoryStoreFullPath = $this->sanitizePath(
            str_replace(
                '/public', 
                $this->sanitizePath($this->generateFilePathFromNamespace(config('model-repository.repositories_namespace'))), 
                public_path()
            )
        );
    }


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {

        parent::setUp();

        $this->generateRepositoryClassStoreFullPath();

        $self = $this;

        $this->beforeApplicationDestroyed(function () use ($self) {

            if ( File::isDirectory($self->repositoryStoreFullPath) ) {

                array_map('unlink', glob($self->repositoryStoreFullPath . '*.*'));

                rmdir($self->repositoryStoreFullPath);
            }
        });
    }


    /**
     * @test
     */
    public function repository_command_will_run() {
        
        $command = $this->artisan('make:repository UserRepository');

        $command->assertExitCode(0);

        $command = $this->artisan('make:repository UserRepository --replace');

        $command->assertExitCode(0);

        $command = $this->artisan('make:repository UserRepository --replace --model=App\\Models\\User');

        $command->assertExitCode(0);
    }


    /**
     * @test
     */
    public function repository_command_will_fail_if_repository_class_not_give() {

        $this->expectException(RuntimeException::class);

        $this->artisan('make:repository');
    }


    /**
     * @test
     */
    public function it_will_throw_exception_if_class_already_exists_and_not_instruct_to_replace() {

        $this->artisan('make:repository', ['class' => 'ProfileRepository']);

        $this->expectException(Exception::class);

        $this->artisan('make:repository', ['class' => 'ProfileRepository']);
    }


    /**
     * @test
     */
    public function it_will_generate_proper_repository_class_at_given_path() {

        $this->artisan('make:repository', ['class' => 'ProfileRepository'])->assertExitCode(0);

        $this->assertTrue(File::exists($this->repositoryStoreFullPath . 'ProfileRepository.php'));
    }


    /**
     * @test
     */
    public function it_will_generate_repository_class_with_proper_content() {

        $this->artisan('make:repository TestRepository --replace --model=Test')->assertExitCode(0);

        $this->assertEquals(
            File::get($this->repositoryStoreFullPath . 'TestRepository.php'),
            File::get(__DIR__ . '/App/Repositories/TestRepository.php'),
        );
    }

}