<?php

namespace Escapepixel\LaravelCAModules\Tests;

use Orchestra\Testbench\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            "Escapepixel\LaravelCAModules\ModuleServiceProvider",
        ];
    }
}
