<?php

namespace Touhidurabir\ModelRepository\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Touhidurabir\ModelRepository\BaseRepository;
use Touhidurabir\ModelRepository\Tests\App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Touhidurabir\ModelRepository\Tests\App\Models\Profile;
use Touhidurabir\ModelRepository\Contracts\RepositoryContract;
use Touhidurabir\ModelRepository\Tests\Traits\LaravelTestBootstrapping;
use Touhidurabir\ModelRepository\Tests\App\Repositories\ProfileRepository;
// use Touhidurabir\ModelRepository\Tests\Traits\LaravelSetup;

/**
 *  TO-DO: Need more testing.
 *  Relation, delete/forceDelete/restore on collection, pagination, where clause, orderBy etc
 */
class RepositoryTest extends TestCase {

    use LaravelTestBootstrapping;

    // use LaravelSetup;

    /**
     * The profile repository
     * 
     * @var object<\Touhidurabir\ModelRepository\Tests\App\Repositories\ProfileRepository>
     */
    protected $profileRepository;


    /**
     * Create test repositories
     * 
     * @return void
     */
    protected function createRepository() {

        $this->profileRepository = new ProfileRepository(new Profile);
    }


    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application $app
     * @return void
     */
    protected function defineEnvironment($app) {

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.url', 'http://localhost/');
        $app['config']->set('app.debug', false);
        $app['config']->set('app.key', env('APP_KEY', '1234567890123456'));
        $app['config']->set('app.cipher', 'AES-128-CBC');
    }


    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations() {

        $this->loadMigrationsFrom(__DIR__ . '/App/database/migrations');
        
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--database' => 'testbench'])->run();
        });
    }


    /**
     * Setup the test environment.
     * 
     * @return void
     */
    protected function setUp(): void {

        // Code before application created.

        parent::setUp();

        // Code after application created.

        $this->createRepository();
    }


    /**
     * @test
     */
    public function will_have_proper_repository_instance() {

        $this->assertTrue($this->profileRepository instanceof ProfileRepository);
        $this->assertTrue($this->profileRepository instanceof BaseRepository);
        $this->assertTrue($this->profileRepository instanceof RepositoryContract);
    }


    /**
     * @test
     */
    public function can_initiate_repository_via_static_method() {

        $alternateProfileRepository = ProfileRepository::withModel(new Profile);

        $this->assertTrue($alternateProfileRepository instanceof ProfileRepository);
        $this->assertTrue($alternateProfileRepository instanceof BaseRepository);
        $this->assertTrue($alternateProfileRepository instanceof RepositoryContract);
    }


    /**
     * @test
     */
    public function repository_class_can_return_back_proper_model_instance() {

        $this->assertTrue($this->profileRepository->getModel() instanceof Profile);
        $this->assertTrue($this->profileRepository->getModel() instanceof Model);
    }


    /**
     * @test
     */
    public function repository_class_can_return_back_proper_model_class() {

        $this->assertEquals($this->profileRepository->getModelClass(), Profile::class);
    }


    /**
     * @test
     */
    public function repository_class_can_check_if_model_has_soft_delete_feature_enabled() {

        $this->assertTrue($this->profileRepository->hasSoftDelete());
    }
    

    /**
     * @test
     */
    public function repository_can_store() {

        $profile = $this->profileRepository->create([
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name'
        ]);

        $this->assertDatabaseHas('profiles', [
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name'
        ]);
    }


    /**
     * @test
     */
    public function repository_can_store_on_given_non_associated_table_columns() {

        $profile = $this->profileRepository->create([
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name',
            'some_column'   => 'Some data'
        ]);

        $this->assertDatabaseHas('profiles', [
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name'
        ]);
    }


    /**
     * @test
     */
    public function repository_can_update() {
        
        $profile = $this->profileRepository->create([
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name'
        ]);

        $this->profileRepository->update([
            'first_name'    => 'New_First_Name',
        ], $profile);

        $this->assertDatabaseHas('profiles', [
            'first_name'    => 'New_First_Name',
            'last_name'     => 'Last_name'
        ]);
    }


    /**
     * @test
     */
    public function repository_can_find() {
        
        $profile = $this->profileRepository->create([
            'first_name'    => 'name',
            'last_name'     => 'Name'
        ]);

        $this->assertEquals($this->profileRepository->find($profile->id)->id, $profile->id);
        $this->assertEquals($this->profileRepository->find(['first_name' => 'name'])->id, $profile->id);
    }

    /**
     * @test
     */
    public function repository_can_find_multiple_as_collection() {
        
        $profile1 = $this->profileRepository->create([
            'first_name'    => 'name1',
            'last_name'     => 'Name1'
        ]);

        $profile2 = $this->profileRepository->create([
            'first_name'    => 'name2',
            'last_name'     => 'Name2'
        ]);

        $this->assertEquals($this->profileRepository->find([$profile1->id, $profile2->id])->count(), 2);
        $this->assertTrue($this->profileRepository->find([$profile1->id, $profile2->id]) instanceof Collection);
    }


    /**
     * @test
     */
    public function repository_can_find_via_array_constrain() {

        $profile = $this->profileRepository->create([
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name'
        ]);

        $this->assertEquals($this->profileRepository->find(['first_name' => 'First_Name'])->id, $profile->id);
        $this->assertEquals($this->profileRepository->find(['first_name' => 'First_Name', 'last_name' => 'Last_name'])->id, $profile->id);
    }


    /**
     * @test
     */
    public function repository_can_throw_exception_on_failed_find() {

        $this->profileRepository->create([
            'first_name'    => 'First_Name',
            'last_name'     => 'Last_name'
        ]);

        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);

        $this->profileRepository->find(100001, [], true);

        $this->profileRepository->find(['first_name' => 'some name'], [], true);
    }


    /**
     * @test
     */
    public function repository_can_delete() {
        
        $profile = $this->profileRepository->create([
            'first_name'    => 'name',
            'last_name'     => 'Name'
        ]);

        $this->profileRepository->delete($profile);

        $this->assertNull($this->profileRepository->find($profile->id));
        $this->assertEquals($this->profileRepository->getModel()->onlyTrashed()->find($profile->id)->id, $profile->id);
    }


    /**
     * @test
     */
    public function repository_can_force_delete() {
        
        $profile = $this->profileRepository->create([
            'first_name'    => 'name',
            'last_name'     => 'Name'
        ]);

        $this->profileRepository->forceDelete($profile);

        $this->assertNull($this->profileRepository->find($profile->id));
        $this->assertNull($this->profileRepository->getModel()->onlyTrashed()->find($profile->id));
    }


    /**
     * @test
     */
    public function repository_can_restore() {
        
        $profile = $this->profileRepository->create([
            'first_name'    => 'name',
            'last_name'     => 'Name'
        ]);

        $this->profileRepository->delete($profile);

        $this->assertNull($this->profileRepository->find($profile->id));

        $this->profileRepository->restore($profile);

        $this->assertEquals($this->profileRepository->find($profile->id)->id, $profile->id);
    }

}