<?php

namespace Exxtensio\EcommerceDashboard\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SingleLocked implements ShouldBroadcastNow
{
    use InteractsWithBroadcasting;

    public string $model;
    public string $prefix;
    public int|string $id;
    public int $userId;

    public function __construct($resourceClass, $request)
    {
        $this->model = str_replace("\\", ".", $resourceClass::$model);
        $this->prefix = $resourceClass::$prefix;
        $this->userId = $request->user()->id;
        $this->id = $request->route()->hasParameter('id') ? $request->route('id') : $request->get('id');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("eloquent.$this->prefix.$this->id"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'locked';
    }
}
