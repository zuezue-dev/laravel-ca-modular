<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Console\Command;
use Escapepixel\LaravelCAModules\Generators\ModuleGenerator;
use Escapepixel\LaravelCAModules\Jobs\FetchMigrationFilePathJob;

class MakeModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name} {--tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Module for a given name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $tenant = $this->option('tenant');

        $success = true;

        $code = with(new ModuleGenerator($name, $tenant))
            ->setFilesystem($this->laravel['files'])
            ->setConfig($this->laravel['config'])
            ->setConsole($this)
            ->generate();

        if ($code === E_ERROR) {
            $success = false;
        }

        if ($tenant) {
            FetchMigrationFilePathJob::dispatchSync();
        }
        
        return $success ? 0 : E_ERROR;
    }
}
