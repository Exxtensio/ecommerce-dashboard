<?php

namespace Exxtensio\EcommerceDashboard\Events;

use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class Test implements ShouldBroadcastNow
{
    use InteractsWithBroadcasting;

    public function __construct()
    {
        //
    }

    public function broadcastOn(): array
    {
        return [];
    }

    public function broadcastAs(): string
    {
        return 'test';
    }
}
