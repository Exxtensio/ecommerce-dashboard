<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasBoolDefault
{
    public bool $default = false;

    public function default(bool $bool): static
    {
        $this->default = $bool;

        return $this;
    }
}
