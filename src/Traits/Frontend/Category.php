<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;

trait Category
{
    /**
     * @param array|null $args
     * @return Collection|array|string
     */
    public static function categories(?array $args = null): Collection|array|string
    {
        $query = Models\ProductCategory::query();
        self::query($query, $args);

        return self::response(
            self::getQuery($query, $args),
            $args['return'] ?? null
        );
    }

    /**
     * @param string $id
     * @param array|null $args
     * @return Model|Collection|array|string
     */
    public static function category(string $id, ?array $args = null): Model|Collection|array|string
    {
        $query = Models\ProductCategory::query();
        self::withQuery($query, $args);

        return self::singleResponse(
            self::findQuery($query, $id, $args),
            $args['return'] ?? null
        );
    }
}
