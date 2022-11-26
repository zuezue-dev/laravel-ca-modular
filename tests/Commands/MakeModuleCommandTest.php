<?php

namespace Escapepixel\LaravelCAModules\Tests\Commands;

use Escapepixel\LaravelCAModules\Tests\BaseTestCase;

class MakeModuleCommandTest extends BaseTestCase
{
    private $centralModulePath = 'modules/central/User';

    private $tenantModulePath = 'modules/tenants/User';

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('make:module', ['name' => 'User']);
        $this->artisan('make:module', ['name' => 'User', '--tenant' => true]);
    }

    public function tearDown(): void
    {
        $this->artisan('delete:module', ['name' => 'User'])
            ->expectsConfirmation("Are you sure you want to delete User module from central?", "yes");

        $this->artisan('delete:module', ['name' => 'User', '--tenant' => true])
            ->expectsConfirmation("Are you sure you want to delete User module from tenants?", "yes");

        parent::tearDown();
    }

    /** @test */
    public function it_generates_module()
    {
        $this->assertDirectoryExists($this->getBasePath() . '/' . $this->centralModulePath);
        $this->assertDirectoryExists($this->getBasePath() . '/' . $this->tenantModulePath);
    }

    /** @test */
    public function it_generates_module_folders()
    {
        foreach (config('modules.paths.generator') as $directory) {
            $this->assertDirectoryExists($this->getBasePath() . '/' . $this->centralModulePath . '/' . $directory);
        }

        foreach (config('modules.paths.generator') as $directory) {
            $this->assertDirectoryExists($this->getBasePath() . '/' . $this->tenantModulePath . '/' . $directory);
        }
    }
}
