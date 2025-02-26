<?php

namespace Exxtensio\EcommerceDashboard\Contracts;

use Closure;
use Exxtensio\EcommerceDashboard\EventLogBag;

interface LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag;
}
