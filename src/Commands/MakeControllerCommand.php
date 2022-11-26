<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Stub;
use Escapepixel\LaravelCAModules\Commands\GeneratorCommand;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create Controller for a given module name';

    /**
     * Get controller name.
     * @return string
     */
    public function getDestinationFilePath()
    {
        if ($this->getTenant()) {
            $path = base_path() . '/modules/' . config("modules.tenant-namespace") . '/' . $this->getStudlyName();
        } else {
            $path = base_path() . '/modules/' . config("modules.central-namespace") . '/' . $this->getStudlyName();
        }

        $controllerPath = GenerateConfigReader::read("controller");

        return $path . '/' . $controllerPath->getNamespace() . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->getStudlyName();

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $this->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $this->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module, 'controller', $this->getTenant()),
            'CLASS'             => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME'        => $this->getLowerName(),
            'MODULE'            => $this->getStudlyName(),
            'NAME'              => $this->getStudlyName(),
            'STUDLY_NAME'       => $this->getStudlyName(),
            'MODULE_NAMESPACE'  => "Modules",
        ]))->render();
    }

    /**
     *  Get Stub name 
     *  @return string 
     */
    public function getStubName()
    {
        return 'controller.stub';
    }

    /**
     *  Get Module Name
     *  @return string 
     */
    public function getModuleName()
    {
        return $this->argument('name');
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
     *  Get Module StudlyName
     *  @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->getModuleName());
    }

    /**
     *  Get Module lowerName
     *  @return string
     */
    public function getLowerName() 
    {
        return strtolower($this->getModuleName());
    }
    

    /**
     *  Get Controller Name
     *  @return string 
     */
    public function getControllerName()
    {
        $controller = Str::studly($this->getModuleName());

        if (Str::contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * @return array|string
     */
    private function getControllerNameWithoutNamespace()
    {
        return class_basename($this->getControllerName());
    }
}
