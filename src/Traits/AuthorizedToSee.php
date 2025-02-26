<?php

namespace Exxtensio\EcommerceDashboard\Traits;

use Closure;
use Illuminate\Http\Request;

trait AuthorizedToSee
{
    public ?Closure $seeCallback;

    public function authorizedToSee(Request $request): bool
    {
        return $this->seeCallback ? call_user_func($this->seeCallback, $request) : true;
    }

    public function canSee(Closure $callback): static
    {
        $this->seeCallback = $callback;

        return $this;
    }
}
