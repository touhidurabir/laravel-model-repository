<?php

namespace Touhidurabir\ModelRepository\Console\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Touhidurabir\ModelRepository\Console\Concerns\ClassFileResolver;

trait StubGenerator {

    use ClassFileResolver;

    /**
     * List of required properties that base class must define
     *
     * @var array
     */
    protected $requiredClassProps = ['stubPath', 'classStorePath'];


    /**
     * Stub file content
     *
     * @var string
     */
    protected $stub = '';


    /**
     * Instance of Filesystem Class
     *
     * @var object
     */
    protected $files;


    /**
     * validate the required properties define in the base class
     *
     * @return boolean
     */
    protected function validate() {

    	foreach ($this->requiredClassProps as $prop) {
    		
    		if ( ! property_exists($this, $prop) ) {
    			
    			$this->error("class must have {$prop} define to generate class file from stub");

    			return false;
    		}
    	}
    	
    	return true;
    }


    /**
     * Save class file generated from stub to the defined location
     *
     * @param  string  $name
     * @param  array   $replacers
     *
     * @return boolean
     */
    protected function saveClass($name, $replacers = []) {

    	if ( ! $this->validate() ) { return false; }

        $this->generateFilePathDirectory($this->classStorePath);

    	$this->files =  new Filesystem;

        if ( ! $this->alreadyExists($name) ) {

            $this->files->put($this->getPath($name), $this->buildClass($replacers));

            return true;
        }

        $this->error("class {$name} already exist at path {$this->classStorePath}");

        return false;
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() {
        
        return str_replace('/Concerns', '', __DIR__) . $this->stubPath;
    }


    /**
     * Build class file content by writing the values in the stub file
     *
     * @param  array   $replacers
     * @return string
     */
    protected function buildClass($replacers = []) {

        $this->stub = $this->files->get($this->getStub());

        foreach ($replacers as $key => $value) {
        	
        	if ( is_array($value) ) {

        		if ( empty ($value) ) {
        			$value = '[]';
        		} else if ( count($value) == 1 && $value[0][0] == '[' && $value[0][strlen($value[0]) - 1] == ']' ) {
        			$value = '["' 
        				. implode(
        					'", "', 
        					array_map(
        						'trim', 
        						explode(
        							',', 
        							str_replace('[', '', str_replace(']', '', $value[0]))
        						)
        					)
        				  )
        				. '"]';
        		} else {
        			$value = '["'.implode('", "', $value).'"]';
        		}

        		$this->replaceInStub('"{{'.$key.'}}"', $value);

        		continue;
        	}

        	$this->replaceInStub('{{'.$key.'}}', $value);
        }

        return $this->stub;
    }


    /**
     * Replace the occurance of target string using the provided value 
     *
     * @param  string  $target
     * @param  string  $content
     *
     * @return $this
     */
    protected function replaceInStub($target ,$content) {
        
        $this->stub = str_replace($target, $content, $this->stub);

        return $this;
    }


    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($name) {

        return $this->files->exists($this->getPath($name));
    }


    /**
     * Get the fully qualified store path
     *
     * @param  string $path
     * @return string
     */
    protected function getStoreDirectoryPath(string $path) {

        return $this->sanitizePath(str_replace('/public', $path, public_path()));
    }


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name) {

        return $this->getStoreDirectoryPath($this->classStorePath) . $name . '.php';
    }


    /**
     * Check if target path directory exists or not
     * If not , create the directory in that path
     *
     * @param  string $path
     * @return void
     */
    protected function generateFilePathDirectory(string $path) {

        $directoryPath = $this->getStoreDirectoryPath($path);

        File::ensureDirectoryExists($directoryPath);
    }
    

    /**
     * Sanitize the path to proper useable path
     * Remove any unecessary slashes
     *
     * @param  string $path
     * @return string
     */
    protected function sanitizePath(string $path) {

        return preg_replace('#/+#','/', "/" . $path . "/");
    }
}