<?php

namespace Mainetcare\Appchup;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mainetcare\Appchup\SeederHelper\SeederHelper\TestHelper\SeederHelper\Skeleton\SkeletonClass
 */
class AppchupFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'appchup';
    }
}
