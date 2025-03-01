<?php

namespace Exxtensio\EcommerceDashboard\Facades;

use Illuminate\Support\Facades\Facade;
use Exxtensio\EcommerceDashboard\LogBatch as ActivityLogBatch;

/**
 * @method static string getUuid()
 * @method static mixed withinBatch(\Closure $callback)
 * @method static void startBatch()
 * @method static void setBatch(string $uuid): void
 * @method static bool isOpen()
 * @method static void endBatch()
 *
 * @see \Exxtensio\EcommerceDashboard\LogBatch
 */
final class LogBatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogBatch::class;
    }
}
