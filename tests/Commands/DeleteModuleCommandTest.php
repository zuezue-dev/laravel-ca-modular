<?php

namespace Escapepixel\LaravelCAModules\Tests\Commands;

use Escapepixel\LaravelCAModules\Tests\BaseTestCase;

class DeleteModuleCommandTest extends BaseTestCase
{
    private $centralModulePath = 'modules/central/User';

    private $tenantModulePath = 'modules/tenants/User';

    /** @test */
    public function it_can_delete_a_module()
    {
        $this->artisan('make:module', ['name' => 'User']);
        $this->artisan('make:module', ['name' => 'User', '--tenant' => true]);

        $this->assertDirectoryExists($this->getBasePath() . '/' . $this->centralModulePath);
        $this->assertDirectoryExists($this->getBasePath() . '/' . $this->tenantModulePath);

        $this->artisan('delete:module', ['name' => 'User'])
            ->expectsConfirmation("Are you sure you want to delete User module from central?", "yes");

        $this->artisan('delete:module', ['name' => 'User', '--tenant' => true])
            ->expectsConfirmation("Are you sure you want to delete User module from tenants?", "yes");

        $this->assertDirectoryDoesNotExist($this->getBasePath() . '/' . $this->centralModulePath);
        $this->assertDirectoryDoesNotExist($this->getBasePath() . '/' . $this->tenantModulePath);
    }
}
