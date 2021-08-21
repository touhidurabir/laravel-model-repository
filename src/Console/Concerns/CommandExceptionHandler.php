<?php

namespace Touhidurabir\ModelRepository\Console\Concerns;

use Throwable;

trait CommandExceptionHandler {

	
	/**
     * Write command exception in the console
     *
     * @param  Exception $exception
     * @param  String    $message
     *
     * @return void
     */
	public function outputConsoleException (Throwable $exception, string $message = null) {

		$this->error($message ?? 'Exception Arise During Task Execution');
            
        $this->error(
            'Exception : ' 
                . $exception->getMessage()
                . " - "
                . $exception->getFile()
                . " at line "
                . $exception->getLine()
        );
	}
}