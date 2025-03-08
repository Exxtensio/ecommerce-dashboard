<?php

namespace Exxtensio\EcommerceDashboard\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class Status implements ShouldBroadcastNow
{
    use InteractsWithBroadcasting;

    public bool $down;

    public function __construct()
    {
        $this->down = app()->isDownForMaintenance();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'status';
    }
}
