<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void success(string $message, array $options = [])
 * @method static void error(string $message, array $options = [])
 * @method static void warning(string $message, array $options = [])
 * @method static void info(string $message, array $options = [])
 *
 * @see \App\Helpers\Alert
 */
class Alert extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'alert';
    }
}