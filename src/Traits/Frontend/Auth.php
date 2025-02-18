<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models\User;

trait Auth
{
    public static function auth(?array $args = null): Model|Collection|array|string
    {
        return self::singleResponse(
            User::select($args['only'] ?? ['id','name','email'])->findOrFail(auth()->id()),
            $args['return'] ?? null
        );
    }
}
