<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait HasDefault
{
    public ?string $default = null;

    public function default(?string $value): static
    {
        $this->default = $value;

        return $this;
    }
}
