<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Escapepixel\LaravelCAModules\Support\Stub;
use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeRouteProviderCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:route-provider {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create Route Service Provider for a given name';


    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->getClassName();

        return (new Stub('/route-provider.stub', [
            'NAMESPACE'            => $this->getClassNamespace($module, 'provider', $this->getTenant()),
            'CLASS'                => $this->getFileName(),
            'API_ROUTES_PATH'      => 'modules/'. $this->getTarget() . '/' . $this->getClassName() . '/' . GenerateConfigReader::read('routes')->getNamespace() . '/api.php'
        ]))->render();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return 'RouteServiceProvider';
    }

    /**
     * Get the destination file path.
     *
     * @return string
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
        return $this->getTenant() ? config('modules.tenant-namespace') : config('modules.central-namespace') ;
    }

}