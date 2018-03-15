<?php

namespace Bantenprov\Nilai\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The Nilai facade.
 *
 * @package Bantenprov\Nilai
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class NilaiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nilai';
    }
}
