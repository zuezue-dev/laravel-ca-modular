<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Console\Command;
use Escapepixel\LaravelCAModules\Generators\FileGenerator;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

abstract class GeneratorCommand extends Command
{
    /**
     * Get template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @return string
     */
    abstract protected function getDestinationFilePath();

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();
        preg_match('/^.*(modules)\/(.*?)$/', $path, $matches);
        try {
            (new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$matches[2]}");
        } catch (\Exception $e) {
            $this->error("File : {$path} already exists.");

            return E_ERROR;
        }

        return 0;
    }

    /**
     *  Get Class Namespace
     *  @return string
     */
    public function getClassNamespace($module, $generator, $tenant)
    {
        $namespace = config("modules.namespace");

        $namespace .= $tenant ? '\\' . config("modules.tenant-namespace") : '\\' . config("modules.central-namespace"); 

        $namespace .= '\\' . $module;

        $namespace .= '\\' . GenerateConfigReader::read($generator)->getNamespace();

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }
}