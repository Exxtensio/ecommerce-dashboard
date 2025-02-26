<?php

namespace Exxtensio\EcommerceDashboard\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Unlocked implements ShouldBroadcastNow
{
    use InteractsWithBroadcasting;

    public string $model;
    public string $prefix;
    public int|string $id;
    public int $userId;

    public function __construct($resourceClass, $request)
    {
        Log::channel('slack')->debug('Unlocked test');
//        $this->model = str_replace("\\", ".", $resourceClass::$model);
//        $this->prefix = $resourceClass::$prefix;
//        $this->userId = $request->user()->id;
//        $this->id = $request->route()->hasParameter('id') ? $request->route('id') : $request->get('id');

//        Cache::delete("$this->model:locked:$this->id");
//        tryBroadcast(new SingleUnlocked($resourceClass, $request));
    }

    public function broadcastOn(): array
    {
        return [
//            new Channel("eloquent.$this->prefix"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'unlocked';
    }
}
