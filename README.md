# Laravel Model UUID

A simple package to use **Repository Pattern** approach for laravel models . 

## Repository pattern
Repositories are classes or components that encapsulate the logic required to access data sources. They centralize common data access functionality, providing better maintainability and decoupling the infrastructure or technology used to access databases from the domain model layer. [Microsoft](https://docs.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/infrastructure-persistence-layer-design)

## Installation

Require the package using composer:

```bash
composer require touhidurabir/laravel-model-repository
```

To publish the config file:
```bash
php artisan vendor:publish --provider="Touhidurabir\ModelRepository\ModelRepositoryServiceProvider" --tag=config
```

## Command and Configuration

To use this package, you need to have repository class bound to laravel model class . This package includes a command that make it easy to to create repository classes from command line . to create a new repository class, run the following command

```bash
php artisan make:repository UserRepository --model=User
```

The above command will create a new repository **UserRepository** class in **App\Repositories** path . the **--model** option to define which laravel model class to target for this repositoty class . The content of **UserRepository** will look like 

```php
namespace App\Repositories;

use Touhidurabir\ModelRepository\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository {

	/**
     * Constructor to bind model to repo
     *
     * @param  object<App\Models\User> $user
     * @return void
     */
    public function __construct(User $user) {

        $this->model = $user;

        $this->modelClass = get_class($user);
    }

}
```

This package by default assume all **models** are located in path **App\Models** and use the path **App\Repositories** to store the **repository** classes. But also possible to provide custom repositories class path and different model class path . for example 

```bash
php artisan make:reposity App\\SomeOtherPath\\UserRepository --model=App\\OtherModelPath\\User
```
The above command will try to store the repository class to path **App\SomeOtherPath** and will create a directory named **SomeOtherPath** if not already exists. Will also try to resolve model path/namespace from **App\OtherModelPath** . 

Check the **config** file after publishing at the **config/model-repository.php** to see the default settings configurations . 

## Usage

The best way to use the repository classes via **Dependency Injection** through the **controller** classes . for example : 

```php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserController extends Controller {

    /**
     * The resource repository instance
     *
     * @var mixed<object{\App\Repositories\UserRepository}|null>
     */
    protected $userRepository;

	/**
     * create a new controller instance
     *
     * @param  \App\Repositories\UserRepository         $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository) {

        $this->userRepository = $userRepository;
    }
}
```

And in that way one can already get a fully qualified user repository class . Also to manually initiated : 

```php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
...

$userRepository = new UserRepository(new User);
```

Or through static constructor
```php
$userRepository = UserRepository::withModel(new User);
```

The repository class will have following features/abilities .

### Create

To create a new model record, just call the **create** method on repositoty class and pass the data attributes as : 

```php
$this->userRepository->create([
    ...
]);
```

### Update

To update a existing model record, call the **update** method of the repository class . the **update** method will require 2 params , the data attributes and the model redored primary key value or an exiting model instance . 

To update with primary key for user with primary key of id with value 10

```php
$primaryKeyValue = 10;

$this->userRepository->update([
    ...
], $primaryKeyValue);
```
or To update the already retrived model record : 

```php
$user; // the already retrived model record instance

$this->userRepository->update([
    ...
], $user);
```

### Find

To find a model record, use the **find** method of the repository class

```php
$this->userRepository->find(1); // find the id(primary key) of 1
$this->userRepository->find([1,2,3]); // find the id(primary key) of 1,2 and 3
```

The **find** method can also work with array where it will use those as **AND WHERE** query and return the first record that match

```php
$this->userRepository->find(['email' => 'somemail@mail.test']);
```

By passing the optional relations array as the second argument to **find** method will load the relations along with model record

```php
$this->userRepository->find(1, ['profile']); // find the id(primary key) of 1
$this->userRepository->find([1,2,3], ['profile']); // find the id(primary key) of 1,2 and 3
```

The thrid agument is a optional boolen which is by default set to **false** . By setting it to **true**, it will thorw the **\Illuminate\Database\Eloquent\ModelNotFoundException** when a model record not found . 

```php
$this->userRepository->find(1, ['profile'],  true); // find the id(primary key) of 1
$this->userRepository->find([1,2,3], [], true); // find the id(primary key) of 1,2 and 3
```

### All Records

To get back all records, use the **all** method of repository class 

```php
$this->userRepository->all();
```

### Delete

To Delete a model record, use the **delete** method of repository class

```php
$this->userRepository->delete(1);
```

The **delete** method can wrok with model instance or the same kind of argument passed to the repository class **find** method . 

```php
$this->userRepository->delete($user); // delete the alredt retrived $user model instance
$this->userRepository->delete(1); // delete user id of 1
$this->userRepository->delete([1,2,3]); // delete user id of 1,2 and 3
$this->userRepository->delete(['email' => 'somemail@mail.test']); // delete user with email of somemail@mail.test
```

The **delete** method also check for the **SoftDelete** feature , that is if the model is using the **Illuminate\Database\Eloquent\SoftDeletes** trait, the it will do the soft delete of given model records. 

### Force Delete

To Force Delete a model record, use the **forceDelete** method of repository class

```php
$this->userRepository->forceDelete(1);
```

The **delete** method can wrok with model instance or the same kind of argument passed to the repository class **find** method . 

```php
$this->userRepository->forceDelete($user); // delete the alredt retrived $user model instance
$this->userRepository->forceDelete(1); // delete user id of 1
$this->userRepository->forceDelete([1,2,3]); // delete user id of 1,2 and 3
$this->userRepository->forceDelete(['email' => 'somemail@mail.test']); // delete user with email of somemail@mail.test
```

The **delete** method also check for the **SoftDelete** feature, that is regardless of the model is using the **Illuminate\Database\Eloquent\SoftDeletes** trait, the it will remove those records from DB.

### Restore

To Restore a model record that has soft deleted, use the **forceDelete** method of repository class

```php
$this->userRepository->restore(1);
```

The **restore** will only works for those models that use the **SoftDeletes** feature . It try to use the restore on the model that do not have **SoftDeletes** implemented, it will throw an exception.

The **restore** method can wrok with model instance or array of model primary keys . 

```php
$this->userRepository->restore($user); // restore the already retrived $user model instance
$this->userRepository->restore(1); // restore user id of 1
$this->userRepository->restore([1,2,3]); // restore user id of 1,2 and 3test
```

## Other Features

### Get Model

As this package does not handle all of the features of Eloquent and if any other Eloquent method need to use to build complex query, we need the model instance . to get the model instance

```php
$this->userRepository->getModel();
```

Also to set/update the model later

```php
$this->userRepository->setModel(new User);
$this->userRepository->setModel($user);
```

### Model Sanitizer

The BaseRepository class includes a model sanitizer that will automatically sanitize passed array attributes on model record create/update . Here sanatize means it will remove any element from the data array to match with the model table schema while at the same time respecting model **$fillable** and **$hidden** properties . 

The implementation of these methods are as such 

```php
/**
 * Sanitize data list to model fillables
 *
 * @param  array   $data
 * @return array
 */
public function sanitizeToModelFillable(array $data) {

    $classModel   = $this->model->getModel();
    $fillable     = $classModel->getFillable();

    $fillables = ! empty($fillable) 
                    ? $fillable 
                    : array_diff(
                        array_diff(
                            Schema::getColumnListing($classModel->getTable()), 
                            $classModel->getGuarded()
                        ), 
                        $classModel->getHidden()
                    );

    return array_intersect_key($data, array_flip($fillables));
}
```

So even if extra details passed, it will be ignored or some columns passed that in the **$fillable** or **$hidden** list. 

```php
$user = $this->userRepository->create([
    'name' => 'User Name',
    'email' => 'somemail@mail.test',
    'password' => Hash::make('password'),
    'date_of_birth' => '1990-12-08' // This date_of_birth column not present in users table
]);
```

The above code will run without any issue while a simple model create method will throw exception . 

```php
$user = $this->userRepository->create($request->validated());

$profile = $this->profileRepository->create($request->validated());
```

This become very useful when in one single controller method do need to push data to multiple model table

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
