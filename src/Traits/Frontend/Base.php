<?php

namespace Exxtensio\EcommerceDashboard\Traits\Frontend;

trait Base
{
    protected static function singleResponse($query, $return)
    {
        if ($return === 'collection') return collect(json_decode(json_encode($query->toArray())));
        elseif ($return === 'array') return $query->toArray();
        elseif ($return === 'json') return $query->toJson();

        return $query;
    }

    protected static function response($query, $return)
    {
        if ($return === 'collection') return collect(json_decode(json_encode($query->toArray())));
        elseif ($return === 'array') return $query->toArray();
        elseif ($return === 'json') return $query->toJson();

        return $query;
    }

    protected static function query($query, $args): void
    {
        self::withQuery($query, $args);
        if (!is_null($args['take'] ?? null)) $query->take($args['take']);
        if (!is_null($args['skip'] ?? null)) $query->skip($args['skip']);
        if(isset($args['orderBy']) && is_callable($args['orderBy'])) $args['orderBy']($query);
        if(isset($args['where']) && is_callable($args['where'])) $args['where']($query);
    }

    protected static function withQuery($query, $args): void
    {
        if (!isset($args['with']) || !$args['with'] || !count($args['with'])) $query->setEagerLoads([]);
        $query->with($args['with'] ?? []);
    }

    protected static function getQuery($query, $args)
    {
        return $query->get($args['only'] ?? ['*']);
    }

    protected static function findQuery($query, $id, $args)
    {
        return $query->select($args['only'] ?? ['*'])->findOrFail($id);
    }
}
