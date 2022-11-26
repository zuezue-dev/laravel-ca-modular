<?php

namespace Escapepixel\LaravelCAModules\Support\Config;

class GeneratorPath
{
    private $namespace;

    public function __construct($config)
    {
        $this->namespace = $config;
    }


    public function getNamespace()
    {
        return $this->namespace;
    }

}
