<?php

namespace Touhidurabir\ModelRepository\Console;

use Throwable;
use Illuminate\Console\Command;
use Touhidurabir\StubGenerator\StubGenerator;
use Touhidurabir\StubGenerator\Concerns\NamespaceResolver;
use Touhidurabir\ModelRepository\Console\Concerns\CommandExceptionHandler;

class Repository extends Command {

    use NamespaceResolver;
    
    /**
     * Process the handeled exception and provide output
     */
    use CommandExceptionHandler;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository
                            {class              : Repository class name}
                            {--model=Model      : Model class name}
                            {--replace          : Should replace an existing one}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repository Class Generator';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';


    /**
     * Class generator stub path
     *
     * @var string
     */
    protected $stubPath = '/stubs/repository.stub';


    /**
     * Generated class store path
     *
     * @var string
     */
    protected $classStorePath;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        
        $this->info('Creating repository class');

        try {

            $this->classStorePath = $this->generateFilePathFromNamespace(
                $this->resolveClassNamespace(
                    $this->argument('class')
                ) ?? config('model-repository.repositories_namespace')
            );

            $saveStatus = (new StubGenerator)
                            ->from($this->generateFullPathOfStubFile($this->stubPath), true)
                            ->to($this->classStorePath, true)
                            ->as($this->resolveClassName($this->argument('class')))
                            ->withReplacers([
                                'class'             => $this->resolveClassName($this->argument('class')),
                                'model'             => $this->resolveClassName($this->option('model')),
                                'modelInstance'     => lcfirst($this->resolveClassName($this->option('model'))),
                                'modelNamespace'    => $this->resolveClassNamespace($this->option('model')) ?? config('model-repository.models_namespace'),
                                'baseClass'         => config('model-repository.base_class'),
                                'baseClassName'     => last(explode('\\', config('model-repository.base_class'))),
                                'classNamespace'    => $this->resolveClassNamespace($this->argument('class')) ?? config('model-repository.repositories_namespace'),
                            ])
                            ->replace($this->option('replace'))
                            ->save();

            if ( $saveStatus ) {

                $this->info('Repository class generated successfully');
            }
            
        } catch (Exception $exception) {
            
            $this->outputConsoleException($exception);
        }
    }


    /**
     * Genrate the stub file full absolute path
     *
     * @param  string $stubRelativePath
     * @return string
     */
    protected function generateFullPathOfStubFile(string $stubRelativePath) {

        return __DIR__ . $stubRelativePath;
    }
    
}
