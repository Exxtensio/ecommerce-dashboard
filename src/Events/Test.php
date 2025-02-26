<?php

namespace Exxtensio\EcommerceDashboard\Events;

use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

class Test implements ShouldBroadcastNow
{
    use InteractsWithBroadcasting;

    public function __construct()
    {
        Log::channel('slack')->debug('test');
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
