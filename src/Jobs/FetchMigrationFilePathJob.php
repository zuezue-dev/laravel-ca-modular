<?php

namespace Escapepixel\LaravelCAModules\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FetchMigrationFilePathJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dirNames = [];
        foreach(scandir(base_path('modules/tenants')) as $dir) {
            if ($dir != '.' && $dir != '..') {
                $dirNames[] = $dir;
            }
        }

        foreach($dirNames as $name) {
            $paths[] = 'modules/tenants/'. $name . '/V1/database/migrations';
        }
        Storage::disk('local')->put('paths.json', stripslashes(json_encode($paths)));
    }
}
