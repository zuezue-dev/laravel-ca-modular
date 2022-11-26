<?php

namespace Escapepixel\LaravelCAModules\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Escapepixel\LaravelCAModules\Traits\ModuleTrait;
use Escapepixel\LaravelCAModules\Jobs\FetchMigrationFilePathJob;

class DeleteModuleCommand extends Command
{
    use ModuleTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:module {name} {--tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Module for a given name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->getStudlyName($this->argument('name'));
        $mainModule = $this->option('tenant') ? 'tenants' : 'central';

        if ($this->confirm("Are you sure you want to delete {$name} module from {$mainModule}?")) {
            if (!$this->isModuleDir($name, $mainModule)) {
                return $this->warn("Module not found!");
            }

            $this->deleteModule($name, $mainModule);
            if ($this->option('tenant')) {
                FetchMigrationFilePathJob::dispatchSync();
            }
            return $this->info("{$name} module from {$mainModule} has been deleted successfully!");
        }
    }
}
