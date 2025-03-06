<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Sortable
{
    public bool $sortable = false;

    public function sortable(bool $value = true): static
    {
        $this->sortable = $value;

        return $this;
    }
}
