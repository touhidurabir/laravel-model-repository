<?php

namespace Touhidurabir\ModelRepository\Tests\Traits;

trait FileHelpers {

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


    /**
     * Generate class store path from give namespace
     *
     * @param  mixed<string|null> $namespace
     * @return mixed<string|null>
     */
    protected function generateFilePathFromNamespace(string $namespace = null) {

        if ( ! $namespace ) {

            return null;
        }

        $namespaceSegments = explode('\\', $namespace);

        return '/' . implode('/', array_values(array_filter($namespaceSegments)));
    }
}