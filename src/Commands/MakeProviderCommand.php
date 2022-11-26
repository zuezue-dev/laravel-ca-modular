<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Stub;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeProviderCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-provider {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create Provider for a given name';

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {

        /** @var Module $module */
        $module = $this->getClassName();

        return (new Stub($this->getStubName(), [
            'NAMESPACE'         => $this->getClassNamespace($module, 'provider', $this->getTenant()),
            'CLASS'             => $this->getClass(),
            'MIGRATIONS_PATH'   => 'modules/'. $this->getTarget() . '/' . $module . '/' . GenerateConfigReader::read('migration')->getNamespace(),
            'INTERFACE'         => $this->getInterface(),
            'IMPLEMENTATION'    => $this->getImplementationClass()
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

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . '/' . $generatorPath->getNamespace() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name')). 'ServiceProvider';
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * @return string
     */
    private function getTenant()
    {
        return $this->option('tenant');
    }

    /**
     * @return string
     */
    private function getTarget()
    {
        return $this->getTenant() ? config('modules.tenant-namespace') : config('modules.central-namespace');
    }

    public function getClass()
    {
        return class_basename($this->getFileName());
    }


    /**
     *  Get Stub Name
     *  @return string
     */
    public function getStubName()
    {
        if ($this->option('tenant')) {
            return '/scaffold/tenant-provider.stub';
        }
        return '/scaffold/provider.stub';
        
    }

    public function getInterface()
    {
        $target = $this->getTarget();

        $path = 'modules/' . $target. '/'. $this->getClassName() . '/' .  GenerateConfigReader::read('driver')->getNamespace();
        $interface = $path . '/' . $this->getClassName() . 'RepositoryInterface';

        return str_replace('/', '\\', $interface);
    }

    public function getImplementationClass()
    {
        $target = $this->getTarget();

        $path = 'modules/' . $target. '/' . $this->getClassName() . '/' .  GenerateConfigReader::read('driver')->getNamespace();
        $className = $path . '/' . $this->getClassName() . 'Repository';

        return str_replace('/', '\\', $className);
    }
}