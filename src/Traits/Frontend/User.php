<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;

trait User
{
    public static function user(string $id, ?array $args = null): Model|Collection|array|string
    {
        $query = Models\User::query();
        self::withQuery($query, $args);

        return self::singleResponse(
            self::findQuery($query, $id, $args),
            $args['return'] ?? null
        );
    }
}
