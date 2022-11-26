<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Escapepixel\LaravelCAModules\Support\Stub;
use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;

class MakeMigrationCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration {name} {--tenant=false}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create migration for a given module name';


    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return Stub::create('/migration/plain.stub', [
            'class' => $this->getClass(),
            'table' => $this->getSchemaName(),
        ]);
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

        $migrationPath = GenerateConfigReader::read("migration");

        return $path . '/' . $migrationPath->getNamespace() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return date('Y_m_d_His_') . 'create_' . $this->getSchemaName() . '_table';
    }

    /**
     * @return string
     */
    private function getTenant()
    {
        return $this->option('tenant');
    }

    /**
     * @return array|string
     */
    private function getSchemaName()
    {
        return Str::plural(Str::lower($this->argument('name')));
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
        return $this->getClassName();
    }

    /**
     * Run the command.
     */
    public function handle() : int
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        if (app()->environment() === 'testing') {
            return 0;
        }

        return 0;
    }
}
