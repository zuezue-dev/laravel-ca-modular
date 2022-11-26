<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Stub;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeDriverImplementCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-driver-implement {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create driver implementation for a given module name';


    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->getClassName();

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module, 'driver', $this->getTenant()),
            'CLASS'     => $this->getClass(),
            'INTERFACE' => $this->getInterface()
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

        $migrationPath = GenerateConfigReader::read("driver");

        return $path . '/' . $migrationPath->getNamespace() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return Str::studly($this->argument('name'));
    }

    public function getClass()
    {
        return class_basename($this->getFileName());
    }

    /**
     *  Get File Name
     *  @return string
     */
    public function getFileName()
    {
        return $this->getClassName() . 'Repository';
    }

     /**
     *  Get Tenant 
     *  @return string
     */
    public function getTenant()
    {
        return $this->option('tenant');
    }

    /**
     *  Get Stub Name
     *  @return string
     */
    public function getStubName()
    {
        return '/driver-implementation.stub';
    }

    /**
     *  Get Interface 
     *  @return string
     */
    public function getInterface()
    {
        return $this->getClassName() . 'RepositoryInterface';
    }
}
