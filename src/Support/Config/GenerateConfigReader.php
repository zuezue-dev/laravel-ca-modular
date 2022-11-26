<?php

namespace Escapepixel\LaravelCAModules\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("modules.paths.generator.$value"));
    }

    public static function getGeneratorList()
    {
        return config("modules.paths.generator");
    }

    public static function getStubFileList()
    {
        return config("modules.stubs.files");
    }
}
