<?php

namespace Escapepixel\LaravelCAModules\Traits;

use Illuminate\Support\Str;

trait ModuleTrait
{
    /**
     * Get the studly name.
     *
     * @return string
     */
    public function getStudlyName($name): string
    {
        return Str::studly($name);
    }

    /**
     * Check if the given module name directory exists.
     *
     * @return bool
     */
    public function isModuleDir($name, $mainModule): bool
    {
        return app()->files->isDirectory(base_path("modules/{$mainModule}/{$name}"));
    }

    /**
     * Delete the module with a given name.
     *
     * @return void
     */
    public function deleteModule($name, $mainModule): void
    {
        app()->files->deleteDirectory(base_path("modules/{$mainModule}/{$name}"));
    }
}
