<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Searchable
{
    public bool $searchable = false;

    public function searchable(): static
    {
        $this->searchable = true;

        return $this;
    }
}
