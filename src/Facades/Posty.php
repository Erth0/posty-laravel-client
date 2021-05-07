<?php

namespace Mukja\Posty\Facades;

use Illuminate\Support\Facades\Facade;

class Posty extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'posty';
    }
}
