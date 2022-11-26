<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;
use Escapepixel\LaravelCAModules\Support\Stub;
use Illuminate\Support\Str;

class MakeTestCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-test {name} {--type=} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create test for a given module name';

    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->getModuleName();

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module, $this->option('type'), $this->getTenant()),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {

        if ($this->getTenant()) {
            $path = base_path() . '/modules/' . config("modules.tenant-namespace") . '/' . $this->getModuleName();
        } else {
            $path = base_path() . '/modules/' . config("modules.central-namespace") . '/' . $this->getModuleName();
        }

        $migrationPath = GenerateConfigReader::read($this->option('type'));

        return $path . '/' . $migrationPath->getNamespace() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getModuleName()
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
        return 'ExampleTest';
    }

    /**
     *  Get Stub Name
     *  @return string
     */
    public function getStubName()
    {
        return '/tests/' . $this->option('type') . '.stub';
    }
}
