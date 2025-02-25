<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;

trait Currency
{
    /**
     * @param array|null $args
     * @return Collection|array|string
     */
    public static function currencies(?array $args = null): Collection|array|string
    {
        $query = Models\Currency::query();
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
    public static function currency(string $id, ?array $args = null): Model|Collection|array|string
    {
        $query = Models\Currency::query();
        self::withQuery($query, $args);

        return self::singleResponse(
            strlen($id) === 3 ? self::findCodeQuery($query, $id, $args) : self::findQuery($query, $id, $args),
            $args['return'] ?? null
        );
    }
}
