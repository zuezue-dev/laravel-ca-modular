<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Stub;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeResourceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-resource {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create resource for a given module name';

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->getClassName();

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module, 'resource', $this->getTenant()),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        if ($this->getTenant()) {
            $path = base_path() . '/modules/' . config("modules.tenant-namespace") . '/' . $this->getClassName();
        } else {
            $path = base_path() . '/modules/' . config("modules.central-namespace") . '/' . $this->getClassName();
        }

        $resourcePath = GenerateConfigReader::read('resource');

        return $path . '/' . $resourcePath->getNamespace() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return $this->getClassName() . 'Resource';
    }

     /**
     * @return string
     */
    private function getTenant()
    {
        return $this->option('tenant');
    }

    /**
     * Get Class Name
     * @return string
     */
    public function getClassName()
    {
        return Str::studly($this->argument('name'));
    }
    /**
     * @return string
     */
    protected function getStubName(): string
    {
        return '/resource.stub';
    }

    /**
     * @return array|string
     */
    private function getClass()
    {
        return class_basename($this->getFileName());
    }
}
