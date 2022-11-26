<?php

namespace Escapepixel\LaravelCAModules\Generators;

use Escapepixel\LaravelCAModules\Support\Config\GenerateConfigReader;
use Escapepixel\LaravelCAModules\Support\Stub;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleGenerator
{
    /**
     * The module name will created.
     *
     * @var string
     */
    protected $name;

    /**
     * The module tenant instance.
     *
     * @var string
     */
    protected $tenant;

    /**
     * The laravel config instance.
     *
     * @var Config
     */
    protected $config;

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The laravel console instance.
     *
     * @var Console
     */
    protected $console;

    /**
     * Force status.
     *
     * @var bool
     */
    protected $force = false;

    /**
     * The constructor.
     * @param $name
     * @param FileRepository $module
     * @param Config     $config
     * @param Filesystem $filesystem
     * @param Console    $console
     */
    public function __construct(
        $name,
        $tenant,
        Config $config = null,
        Filesystem $filesystem = null,
        Console $console = null,
    ) {
        $this->name = $name;
        $this->tenant = $tenant;
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->console = $console;
    }

    /**
     * Get the name of module will created. By default in studly case.
     *
     * @return string
     */
    public function getName()
    {
        return Str::studly($this->name);
    }

     /**
     * Get the name of module will created. By default in studly case.
     *
     * @return string
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Get the laravel config instance.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the laravel config instance.
     *
     * @param Config $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the laravel filesystem instance.
     *
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get the laravel console instance.
     *
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Set the laravel console instance.
     *
     * @param Console $console
     *
     * @return $this
     */
    public function setConsole($console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get the list of folders will created.
     *
     * @return array
     */
    public function getFolders()
    {
        return GenerateConfigReader::getGeneratorList();
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {
        return GenerateConfigReader::getStubFileList();
    }

    /**
     * Generate the module.
     */
    public function generate(): int
    {
        $name = $this->getName();

        if (File::isDirectory('modules/' . $this->getTarget() . '/' . $name)) {

            $this->console->error("Module [{$name}] already exist!");

            return E_ERROR;
        }

        $this->generateFolders();

        $this->generateFiles();
        $this->generateResources();

        $this->console->info("Module [{$name}] created successfully.");

        return 0;
    }

    /**
     * Generate the folders.
     */
    public function generateFolders()
    {
        foreach ($this->getFolders() as $key => $folder) {
            $folder = GenerateConfigReader::read($key);

            $path = $this->getModulePath($this->getName()) . '/' . $folder->getNamespace();

            $this->filesystem->makeDirectory($path, 0755, true);
        }
    }

    /**
     * Generate the files.
     */
    public function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->getModulePath($this->getName()) . $file;

            if (!$this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($path, $this->getStubContents($stub));

            preg_match('/^.*(modules)\/(.*?)$/', $path, $matches);
            $this->console->info("Created : {$matches[2]}");
        }
    }

    /**
     * Generate some resources.
     */
    public function generateResources()
    {
        // if (GenerateConfigReader::read('provider')->generate() === true) {
        //     $this->console->call('module:make-provider', [
        //         'name' => $this->getName() . 'ServiceProvider',
        //         'module' => $this->getName(),
        //         '--master' => true,
        //     ]);
        //     $this->console->call('module:route-provider', [
        //         'module' => $this->getName(),
        //     ]);
        // }
        $this->console->call('make:model', ['name' => $this->getTenant() ? 'Tenant/V1/'.$this->getName() : 'Central/V1/'.$this->getName()]);
        $this->console->call('module:make-provider', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:route-provider', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);

        $this->console->call('module:make-migration', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-controller', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-request', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-resource', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-entity', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-driver-interface', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-driver-implement', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-usecase-interface', ['name' => $this->getName(), '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-test', ['name' => $this->getName(), '--type' => 'feature',  '--tenant' => $this->getTenant() ?? false]);
        $this->console->call('module:make-test', ['name' => $this->getName(), '--type' => 'unit',  '--tenant' => $this->getTenant() ?? false]);
    }

    public function getModulePath($module)
    {
        if ($this->getTenant()) {
            return base_path() . '/modules/tenants/' . $module . '/';
        }
        return base_path() . '/modules/central/' . $module . '/';
    }

    /**
     * Get the contents of the specified stub file by given stub name.
     *
     * @param $stub
     *
     * @return string
     */
    protected function getStubContents($stub)
    {
        return (new Stub(
            '/' . $stub . '.stub'
        )
        )->render();
    }

    /**
     * Get the module name in lower case.
     *
     * @return string
     */
    protected function getLowerNameReplacement()
    {
        return strtolower($this->getName());
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getStudlyNameReplacement()
    {
        return $this->getName();
    }

    /**
     * Get replacement for $MODULE_NAMESPACE$.
     *
     * @return string
     */
    protected function getModuleNamespaceReplacement()
    {
        return str_replace('\\', '\\\\', $this->module->config('namespace'));
    }

    protected function getProviderNamespaceReplacement(): string
    {
        return str_replace('\\', '\\\\', GenerateConfigReader::read('provider')->getNamespace());
    }

    protected function getTarget()
    {
        return $this->tenant ? 'tenants' : 'central';
    }
}
