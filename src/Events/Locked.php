<?php

namespace Exxtensio\EcommerceDashboard\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Cache;

class Locked implements ShouldBroadcastNow
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

        Cache::put("$this->model:locked:$this->id", $this->userId);
//        tryBroadcast(new SingleLocked($resourceClass, $request));
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("eloquent.$this->prefix"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'locked';
    }
}
