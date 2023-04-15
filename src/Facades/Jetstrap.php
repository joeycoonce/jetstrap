<?php

namespace JoeyCoonce\Jetstrap\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JoeyCoonce\Jetstrap\Jetstrap
 */
class Jetstrap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JoeyCoonce\Jetstrap\Jetstrap::class;
    }
}
