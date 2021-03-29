<?php

namespace Mainetcare\Appchup\Tests;

use Mainetcare\Appchup\AppchupServiceProvider;

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
