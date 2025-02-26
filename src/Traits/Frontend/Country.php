<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Exxtensio\EcommerceDashboard\Models;

trait Country
{
    /**
     * @param array|null $args
     * @return Collection|array|string
     */
    public static function activeCountries(?array $args = null): Collection|array|string
    {
        $query = Models\Country::where('active', 1);
        self::query($query, collect($args)->only(['with'])->toArray());

        return self::response(
            self::getQuery($query, $args),
            $args['return'] ?? null
        );
    }

    /**
     * @param array|null $args
     * @return Collection|array|string
     */
    public static function countries(?array $args = null): Collection|array|string
    {
        $query = Models\Country::query();
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
    public static function country(string $id, ?array $args = null): Model|Collection|array|string
    {
        $query = Models\Country::query();
        self::withQuery($query, $args);

        return self::singleResponse(
            strlen($id) === 2 ? self::findCodeQuery($query, $id, $args) : self::findQuery($query, $id, $args),
            $args['return'] ?? null
        );
    }
}
