<?php

namespace Mainetcare\Appchup\SeederHelper\SeederHelper\TestHelper\SeederHelper\Tests;

use Orchestra\Testbench\TestCase;
use Mainetcare\Appchup\SeederHelper\SeederHelper\TestHelper\SeederHelper\AppchupServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [AppchupServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
