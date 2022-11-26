<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Stub;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeUseCaseInterfaceCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-usecase-interface {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create usecase interface for a given module name';


    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->getClassName();

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module, 'usecase', $this->getTenant()),
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
        
        $migrationPath = GenerateConfigReader::read("usecase");

        return $path . '/' . $migrationPath->getNamespace() . '/' . $this->getFileName() . '.php';
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
        return $this->getClassName() . 'UseCaseInterface';
    }

    /**
     *  Get Stub Name
     *  @return string
     */
    public function getStubName()
    {
        return '/usecase-interface.stub';
    }
}
