<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Makeable
{
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }
}
