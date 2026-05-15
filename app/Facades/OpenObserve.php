<?php

namespace App\Facades;

use App\Logging\OpenObserve\OpenObserveLogger;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void log(string $level, string $message, array $context = [])
 * @method static bool raw(array $row)
 * @method static \Monolog\Logger monolog()
 *
 * @see \App\Logging\OpenObserve\OpenObserveLogger
 */
class OpenObserve extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OpenObserveLogger::class;
    }
}
